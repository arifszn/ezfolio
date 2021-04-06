import React, { useEffect, useState } from 'react';
import { Card, Col, Row, Radio, Spin, Image, PageHeader, List } from 'antd';
import Utils from '../../../common/helpers/Utils';
import styled from 'styled-components';
import HTTP from '../../../common/helpers/HTTP';
import Routes from '../../../common/helpers/Routes';
import Constants from '../../../common/helpers/Constants';
import PropTypes from 'prop-types';

const { Meta } = Card;
const { Item } = List;

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
    const [loading, setLoading] = useState(false);
    const [currentSettingToChange, setCurrentSettingToChange] = useState(null);

    const templateOnClickHandler = (selected) => {
        if (selected !== template) {
            const value = selected;

            const callback = () => {
                setTemplate(selected);
            }
            submitData(Constants.portfolioConfig.TEMPLATE, value, callback);
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

    useEffect(() => {
        if (props.config) {
            setTemplate(props.config.template);            
        }
    }, [props.config])

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
                    <Spin delay={500} size="small" spinning={loading && currentSettingToChange === Constants.portfolioConfig.TEMPLATE}>
                        <StyledListItem style={{padding: '16px 0px'}}>
                            <Item.Meta title={'Portfolio Template'} description={changeTemplate}/>
                        </StyledListItem>
                    </Spin>
                    <Spin delay={500} size="small" spinning={loading && currentSettingToChange === Constants.portfolioConfig.ACCENT_COLOR}>
                        <StyledListItem actions={
                            [
                                <a 
                                    key="site-name-change" 
                                    onClick={() => {
                                        
                                    }}
                                >
                                    Change
                                </a>,
                            ]
                        }>
                            <Item.Meta title={'Portfolio Accent Color'} description={'Change accent color of portfolio.'} />
                        </StyledListItem>
                    </Spin>
                </List>
            </PageHeader>
        </React.Fragment>
    );
};

Basic.propTypes = {
    config: PropTypes.object,
}

export default Basic;