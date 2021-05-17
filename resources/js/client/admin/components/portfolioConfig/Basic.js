import React, { useEffect, useState } from 'react';
import { Card, Col, Row, Radio, Spin, Image, PageHeader, List, Typography, Switch } from 'antd';
import Utils from '../../../common/helpers/Utils';
import styled from 'styled-components';
import HTTP from '../../../common/helpers/HTTP';
import Routes from '../../../common/helpers/Routes';
import CoreConstants from '../../../common/helpers/CoreConstants';
import PropTypes from 'prop-types';
import ColorPickerPopup from '../ColorPickerPopup';
import { CloseOutlined, CheckOutlined } from '@ant-design/icons';
import SeoPopup from '../SeoPopup';
import { useSelector } from 'react-redux';

const { Meta } = Card;
const { Item } = List;
const { Paragraph, Text } = Typography;

const selectedTemplateStyle = {
    padding: '.25rem',
    cursor: 'default',
    backgroundColor: 'var(--primary-shadow-color)'
}

const LazyLoadPlaceHolderWrapper = styled.div`
background: ghostwhite;
width: 100%;
height: 100%;
align-items: center;
justify-content: center;
-webkit-box-align: center;
display: inline-flex;
`;

const StyledListItem = styled(Item)`
padding: 16px 0px !important;
`;

const Basic = (props) => {
    const [template, setTemplate] = useState(null);
    const [accentColor, setAccentColor] = useState(null);
    const [googleAnalyticsId, setGoogleAnalyticsId] = useState('');
    const [maintenanceMode, setMaintenanceMode] = useState(false);
    const [loading, setLoading] = useState(false);
    const [currentSettingToChange, setCurrentSettingToChange] = useState(null);
    const [colorPickerVisible, setColorPickerVisible] = useState(false);
    const [seoPopupVisible, setSeoPopupVisible] = useState(false);
    const [seo, setSeo] = useState(null);
    const { demoMode } = useSelector(state => state.globalState);

    useEffect(() => {
        props.mountedCallBack();
    }, [])

    useEffect(() => {
        if (props.config) {
            setTemplate(props.config.template);
            setAccentColor(props.config.accentColor);     
            setGoogleAnalyticsId(props.config.googleAnalyticsId);
            setSeo(props.config.seo);
            setMaintenanceMode((parseInt(props.config.maintenanceMode) == 1) ? true : false);  
        }
    }, [props.config])

    const colorPickerSubmitCallback = (color) => {
        submitData(CoreConstants.portfolioConfig.ACCENT_COLOR, color);
    }

    const colorPickerOnChange = (colorObject) => {
        const color = colorObject.hex;
        setAccentColor(color);
    }

    const colorPickerCancelCallback = (color = null) => {
        if (color) {
            setAccentColor(color);
        }

        setColorPickerVisible(false);
    }

    const templateOnClickHandler = (selected) => {
        if (selected !== template) {
            const value = selected;

            const callback = () => {
                setTemplate(selected);
            }
            submitData(CoreConstants.portfolioConfig.TEMPLATE, value, callback);
        }
    }
    
    const submitData = (name, value, callback = null) => {
        if (!loading) {
            setLoading(true);
        }

        setCurrentSettingToChange(name);

        HTTP.post(Routes.api.admin.portfolioConfigs, {
            setting_key: name,
            setting_value: value
        })
        .then(response => {
            Utils.handleSuccessResponse(response, () => {
                if (callback) {
                    callback();
                }
            })
        })
        .catch(error => {
            Utils.handleException(error);
        })
        .finally(() => {
            setLoading(false);
        });
    }

    const changeGoogleAnalyticsId = (
        <React.Fragment>
            {
                googleAnalyticsId ? (
                    <Paragraph
                            editable={{
                                tooltip: 'click to edit google analytic id',
                                onChange: setGoogleAnalyticsId,
                            }}
                        >
                        {googleAnalyticsId}
                    </Paragraph>
                ) : (
                    <React.Fragment>
                        <Paragraph
                            editable={{
                                tooltip: 'click to edit google analytic id',
                                onChange: setGoogleAnalyticsId,
                            }}
                        >
                            <Text type='secondary'>Click here to set google analytic id</Text>
                        </Paragraph>
                    </React.Fragment>
                )
            }
        </React.Fragment>
    )

    const changeGoogleAnalyticsIdHandleSubmit = (e) => {
        e.preventDefault();

        submitData(CoreConstants.portfolioConfig.GOOGLE_ANALYTICS_ID, googleAnalyticsId);
    }

    const maintenanceModeOnChange = (checked) => {
        if (demoMode) {
            Utils.showNotification('This feature is disabled in demo', 'warning');
        } else {
            const callback = () => {
                setMaintenanceMode(checked);
            }

            submitData(CoreConstants.portfolioConfig.MAINTENANCE_MODE, checked, callback);
        }
    }

    const changeTemplate = (
        <Radio.Group onChange={(e) => templateOnClickHandler(e.target.value)} value={template}>
            <Row gutter={24}>
                {
                    Utils.templates.map((element, index) => {
                        return (
                            <Col key={index} xs={{ span: 24 }} lg={{ span: 8 }} style={{
                                marginBottom: 24,
                            }}>
                                <Card
                                    bodyStyle={{padding: 8}}
                                    style={element.id === template ? selectedTemplateStyle : {}}
                                    onClick={() => templateOnClickHandler(element.id)}
                                    cover={
                                        <Image
                                            className="z-shadow"
                                            alt={element.title}
                                            width={'100%'}
                                            height={155}
                                            preview={false}
                                            src={element.image}
                                            placeholder={
                                                <LazyLoadPlaceHolderWrapper>
                                                    <Spin/>
                                                </LazyLoadPlaceHolderWrapper>
                                            }
                                        />
                                    }
                                >
                                    <Meta
                                        title={<Radio value={element.id}>{element.title}</Radio>}
                                    />
                                </Card>
                            </Col>
                        )
                    })
                }
            </Row>
        </Radio.Group>
    )

    return (
        <React.Fragment>
            <PageHeader
                title="Basic Config"
            >
                <List
                    itemLayout="horizontal"
                    size="large"
                >
                    <Spin delay={500} size="small" spinning={loading && currentSettingToChange === CoreConstants.portfolioConfig.TEMPLATE}>
                        <StyledListItem style={{padding: '16px 0px'}}>
                            <Item.Meta title={'Portfolio Template'} description={changeTemplate}/>
                        </StyledListItem>
                    </Spin>
                    <Spin delay={500} size="small" spinning={loading && currentSettingToChange === CoreConstants.portfolioConfig.ACCENT_COLOR}>
                        <StyledListItem actions={
                            [
                                <a 
                                    key="accent-color-change" 
                                    onClick={() => {
                                        setColorPickerVisible(true);
                                    }}
                                >
                                    Change
                                </a>,
                            ]
                        }>
                            <Item.Meta title={'Portfolio Accent Color'} description={'Change accent color of frontend.'} />
                        </StyledListItem>
                    </Spin>
                    <Spin delay={500} size="small" spinning={loading && currentSettingToChange === CoreConstants.portfolioConfig.GOOGLE_ANALYTICS_ID}>
                        <StyledListItem actions={[
                            <a 
                                key="google-analytics-change" 
                                onClick={changeGoogleAnalyticsIdHandleSubmit}
                            >
                                Change
                            </a>,
                        ]}>
                            <Item.Meta title={'Google Analytics ID'} description={changeGoogleAnalyticsId}/>
                        </StyledListItem>
                    </Spin>
                    <StyledListItem actions={
                        [
                            <a 
                                key="seo-change" 
                                onClick={() => {
                                    setSeoPopupVisible(true);
                                }}
                            >
                                Change
                            </a>,
                        ]
                    }>
                        <Item.Meta title={'SEO'} description={'Search engine optimization of frontend.'} />
                    </StyledListItem>
                    <Spin delay={500} size="small" spinning={loading && currentSettingToChange === CoreConstants.portfolioConfig.MAINTENANCE_MODE}>
                        <StyledListItem actions={[
                            <Switch
                                key="maintenance-mode-change" 
                                loading={loading && currentSettingToChange === CoreConstants.portfolioConfig.MAINTENANCE_MODE}
                                checkedChildren={<CheckOutlined />}
                                unCheckedChildren={<CloseOutlined />}
                                checked={maintenanceMode}
                                onChange={maintenanceModeOnChange}
                            />
                        ]}>
                            <Item.Meta title={'Maintenance Mode'} description={'Set maintenance mode of frontend.'}/>
                        </StyledListItem>
                    </Spin>
                </List>
            </PageHeader>
            {
                colorPickerVisible && (
                    <ColorPickerPopup
                        selectedColor={accentColor}
                        visible={colorPickerVisible}
                        handleCancel={colorPickerCancelCallback}
                        submitCallback={colorPickerSubmitCallback}
                        colorPickerOnChange={colorPickerOnChange}
                    />
                )
            }
            {
                seoPopupVisible && (
                    <SeoPopup
                        visible={seoPopupVisible}
                        data={seo}
                        handleCancel={
                            () => {
                                setSeoPopupVisible(false);
                            }
                        }
                        submitCallback={
                            (newValue) => {
                                setSeo(newValue);
                                setSeoPopupVisible(false);
                            }
                        }
                    />
                )
            }
        </React.Fragment>
    );
};

Basic.propTypes = {
    config: PropTypes.object,
    mountedCallBack: PropTypes.func.isRequired
}

export default Basic;