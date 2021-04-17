import React, { useEffect, useState } from 'react';
import { Spin, List, PageHeader } from 'antd';
import PropTypes from 'prop-types';
import HTTP from '../../../common/helpers/HTTP';
import Routes from '../../../common/helpers/Routes';
import Utils from '../../../common/helpers/Utils';
import styled from 'styled-components';
import CoreConstants from '../../../common/helpers/CoreConstants';
import CodeEditorPopup from '../CodeEditorPopup';

const { Item } = List;

const StyledListItem = styled(Item)`
padding: 16px 0px !important;
`;

const CustomScript = (props) => {
    const [currentSettingToChange, setCurrentSettingToChange] = useState(null);
    const [loading, setLoading] = useState(false);
    const [headerScript, setHeaderScript] = useState('');
    const [footerScript, setFooterScript] = useState('');
    const [headerCodeEditorVisible, setHeaderCodeEditorVisible] = useState(false);
    const [footerCodeEditorVisible, setFooterCodeEditorVisible] = useState(false);

    useEffect(() => {
        props.mountedCallBack();
    }, [])

    useEffect(() => {
        if (props.config) {
            setHeaderScript(props.config.script.header);
            setFooterScript(props.config.script.footer);
        }
    }, [props.config])

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

    return (
        <React.Fragment>
            <PageHeader
                title="Custom Script"
            >
                <List
                    itemLayout="horizontal"
                    size="large"
                >
                    <Spin delay={500} size="small" spinning={loading && currentSettingToChange === CoreConstants.portfolioConfig.SCRIPT_HEADER}>
                        <StyledListItem actions={
                            [
                                <a 
                                    key="header" 
                                    onClick={() => {
                                        setHeaderCodeEditorVisible(true);
                                    }}
                                >
                                    Change
                                </a>,
                            ]
                        }>
                            <Item.Meta title={'Portfolio Header Script'} description={'Set script which will be placed in header.'} />
                        </StyledListItem>
                    </Spin>
                    <Spin delay={500} size="small" spinning={loading && currentSettingToChange === CoreConstants.portfolioConfig.SCRIPT_FOOTER}>
                        <StyledListItem actions={
                            [
                                <a 
                                    key="footer" 
                                    onClick={() => {
                                        setFooterCodeEditorVisible(true);
                                    }}
                                >
                                    Change
                                </a>,
                            ]
                        }>
                            <Item.Meta title={'Portfolio Header Script'} description={'Set script which will be placed in footer.'} />
                        </StyledListItem>
                    </Spin>
                </List>
            </PageHeader>
            {
                headerCodeEditorVisible && (
                    <CodeEditorPopup
                        title={'Header Script'}
                        value={headerScript}
                        loading={loading && currentSettingToChange === CoreConstants.portfolioConfig.SCRIPT_HEADER}
                        visible={headerCodeEditorVisible}
                        handleCancel={
                            () => {
                                setHeaderCodeEditorVisible(false);
                            }
                        }
                        submitCallback={
                            (value) => {
                                const callback = () => {
                                    setHeaderScript(value);
                                    setHeaderCodeEditorVisible(false);
                                }
                                submitData(CoreConstants.portfolioConfig.SCRIPT_HEADER, value, callback);
                            }
                        }
                    />
                )
            }
            {
                footerCodeEditorVisible && (
                    <CodeEditorPopup
                        title={'Footer Script'}
                        value={footerScript}
                        loading={loading && currentSettingToChange === CoreConstants.portfolioConfig.SCRIPT_FOOTER}
                        visible={footerCodeEditorVisible}
                        handleCancel={
                            () => {
                                setFooterCodeEditorVisible(false);
                            }
                        }
                        submitCallback={
                            (value) => {
                                const callback = () => {
                                    setFooterScript(value);
                                    setFooterCodeEditorVisible(false);
                                }
                                submitData(CoreConstants.portfolioConfig.SCRIPT_FOOTER, value, callback);
                            }
                        }
                    />
                )
            }
        </React.Fragment>
    );
};

CustomScript.propTypes = {
    config: PropTypes.object,
    mountedCallBack: PropTypes.func.isRequired
}

export default CustomScript;