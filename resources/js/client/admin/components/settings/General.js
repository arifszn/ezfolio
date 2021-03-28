import { List, PageHeader, Input, Typography  } from 'antd';
import React, { useState } from 'react';
import { connect } from 'react-redux';
import Utils from '../../../common/helpers/Utils';
import { setGlobalState } from '../../redux/ActionCreators';
import FileUploader from '../uploader/FileUploader';
import PropTypes from 'prop-types';
import LoginCredentialPopup from './LoginCredentialPopup';

const { Paragraph } = Typography;

const General = (props) => {
    const [loginCredentialVisible, setLoginCredentialVisible] = useState(false);
    const [siteName, setSiteName] = useState(props.globalState.siteName);

    const changeSiteName = (
        <Paragraph
            editable={{
                tooltip: 'click to edit app name',
                onChange: setSiteName,
                maxLength: 20,
            }}
        >
        {siteName}
      </Paragraph>
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
                    <List.Item 
                        actions={[
                            <a key="login-credentials-change" onClick={(e) => {
                                e.preventDefault();
                                if (props.globalState.demoMode) {
                                    Utils.showNotification('This feature is not available in Demo', 'warning');
                                } else {
                                    setLoginCredentialVisible(true);
                                }
                            }}>
                                Change
                            </a>,
                        ]}
                    >
                        <List.Item.Meta title={'Login Credentials'} description={'Change your login credentials'} />
                    </List.Item>
                    <List.Item actions={(siteName && props.globalState.siteName !== siteName) && (
                        [
                            <a 
                                key="site-name-change" 
                                onClick={(e) => {
                                    e.preventDefault();
                                }}
                            >
                                Change
                            </a>,
                        ]
                    )}>
                        <List.Item.Meta title={'App Name'} description={changeSiteName}>
                        </List.Item.Meta>
                    </List.Item>
                    <List.Item>
                        <List.Item.Meta title={'App Logo'} description={
                            <FileUploader
                                allowRevert={true}
                                previewFile={Utils.backend + '/' + props.globalState.logo}
                                apiToken={'dasdasdasd'}
                                acceptedFileTypes={['image/png', 'image/jpeg', 'image/jpg', 'image/svg+xml']}
                                allowMultiple={false}
                                name={'filePond'}
                                serverUrl={'#'}
                                labelIdle={'Drag & Drop your logo or <span class="filepond--label-action">Browse</span>'}
                                afterUploadCallback={() => {}}
                                afterRevertCallback={() => {}}
                            />
                        }>
                        </List.Item.Meta>
                    </List.Item>
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
    
}

export default connect(mapStateToProps, mapDispatchToProps)(General);