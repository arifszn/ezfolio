import { List, PageHeader, Typography, Spin } from 'antd';
import React, { useState } from 'react';
import { connect } from 'react-redux';
import Utils from '../../../common/helpers/Utils';
import { setGlobalState } from '../../redux/ActionCreators';
import FileUploader from '../uploader/FileUploader';
import PropTypes from 'prop-types';
import LoginCredentialPopup from './LoginCredentialPopup';
import CoreConstants from '../../../common/helpers/CoreConstants';
import HTTP from '../../../common/helpers/HTTP';
import Routes from '../../../common/helpers/Routes';
import styled from 'styled-components';

const { Paragraph } = Typography;
const { Item } = List;

const StyledListItem = styled(Item)`
padding: 16px 0px !important;
`;

const General = (props) => {
    const [loginCredentialVisible, setLoginCredentialVisible] = useState(false);
    const [siteName, setSiteName] = useState(props.globalState.siteName);
    const [currentSettingToChange, setCurrentSettingToChange] = useState(null);
    const [loading, setLoading] = useState(false);

    const submitData = (name, value, callback = null) => {
        if (!loading) {
            setLoading(true);
        }

        setCurrentSettingToChange(name);

        HTTP.post(Routes.api.admin.settings, {
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

    const changeSiteNameHandleSubmit = (e) => {
        e.preventDefault();

        const callback = () => {
            props.setGlobalState({
                siteName: siteName
            });
        }

        submitData(CoreConstants.settings.SITE_NAME, siteName, callback);
    }

    const changeSiteName = (
        <Paragraph
            editable={{
                tooltip: 'click to edit app name',
                onChange: setSiteName,
                maxLength: 30,
            }}
        >
        {siteName}
      </Paragraph>
    )

    const logoUploadCallback = (file) => {
        setTimeout(() => {
            props.setGlobalState({
                logo: file
            });
        }, 2000);
    }

    const faviconUploadCallback = (file) => {
        setTimeout(() => {
            props.setGlobalState({
                favicon: file
            });
        }, 2000);
        document.getElementById("favicon").href = Utils.backend + '/' + file;
    }

    const changeLogo = (
        <FileUploader
            allowRevert={false}
            previewFile={Utils.backend + '/' + props.globalState.logo}
            acceptedFileTypes={"image/*"}
            allowMultiple={false}
            name={'file'}
            serverUrl={Routes.api.admin.logo}
            labelIdle={'Drag & Drop your logo or <span class="filepond--label-action">Browse</span>'}
            afterUploadCallback={logoUploadCallback}
            afterRevertCallback={logoUploadCallback}
        />
    )

    const changeFavicon = (
        <FileUploader
            allowRevert={false}
            previewFile={Utils.backend + '/' + props.globalState.favicon}
            acceptedFileTypes={"image/*"}
            allowMultiple={false}
            name={'file'}
            serverUrl={Routes.api.admin.favicon}
            labelIdle={'Drag & Drop your favicon or <span class="filepond--label-action">Browse</span>'}
            afterUploadCallback={faviconUploadCallback}
            afterRevertCallback={faviconUploadCallback}
            imagePreviewMaxHeight={75}
        />
    )

    return (
        <React.Fragment>
            <PageHeader
                title="General Settings"
            >
                <List
                    itemLayout="horizontal"
                    size="large"
                >
                    <StyledListItem
                        actions={[
                            <a key="login-credentials-change" onClick={(e) => {
                                e.preventDefault();
                                if (props.globalState.demoMode) {
                                    Utils.showNotification('This feature is disabled in demo', 'warning');
                                } else {
                                    setLoginCredentialVisible(true);
                                }
                            }}>
                                Change
                            </a>,
                        ]}
                    >
                        <Item.Meta title={'Login Credentials'} description={'Change your login credentials.'} />
                    </StyledListItem>
                    <Spin delay={500} size="small" spinning={loading && currentSettingToChange === CoreConstants.settings.SITE_NAME}>
                        <StyledListItem actions={(siteName && props.globalState.siteName !== siteName) && (
                            [
                                <a 
                                    key="site-name-change" 
                                    onClick={changeSiteNameHandleSubmit}
                                >
                                    Change
                                </a>,
                            ]
                        )}>
                            <Item.Meta title={'App Name'} description={changeSiteName}/>
                        </StyledListItem>
                    </Spin>
                    <StyledListItem style={{padding: '16px 0px'}}>
                        <Item.Meta title={'App Logo'} description={changeLogo}/>
                    </StyledListItem>
                    <StyledListItem style={{padding: '16px 0px'}}>
                        <Item.Meta title={'Favicon'} description={changeFavicon}/>
                    </StyledListItem>
                </List>
            </PageHeader>
            {
                loginCredentialVisible && (
                    <LoginCredentialPopup
                        visible={loginCredentialVisible}
                        handleCancel={
                            () => {
                                setLoginCredentialVisible(false);
                            }
                        }
                        submitCallback={
                            () => {
                                setLoginCredentialVisible(false);
                            }
                        }
                    />
                )
            }
        </React.Fragment>
    )
}

const mapStateToProps = state => {
    return {
        globalState: state.globalState
    };
};

const mapDispatchToProps = dispatch => ({
    setGlobalState: (state) => dispatch(setGlobalState(state)),
});

General.propTypes = {
    globalState: PropTypes.object,
    setGlobalState: PropTypes.func
}

export default connect(mapStateToProps, mapDispatchToProps)(General);