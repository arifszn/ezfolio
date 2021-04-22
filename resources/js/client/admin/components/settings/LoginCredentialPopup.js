import React, { useEffect, useState } from 'react';
import { Drawer, Input, Button, Form, Spin } from 'antd';
import styled from 'styled-components';
import HTTP from '../../../common/helpers/HTTP';
import Routes from '../../../common/helpers/Routes';
import Utils from '../../../common/helpers/Utils';
import PropTypes from 'prop-types';

const StyledDrawer = styled(Drawer)`
    .ant-drawer-content-wrapper {
        width: 520px !important;
        @media (max-width: 768px) {
            max-width: calc(100vw - 16px) !important;
        }
    }
`;

const LoginCredentialPopup = (props) => {
    const [visible, setVisible] = useState(false);
    const [loading, setLoading] = useState(false);
    const [componentLoading, setComponentLoading] = useState(true);
    const [form] = Form.useForm();

    useEffect(() => {
        loadData();
    }, [])

    useEffect(() => {
        setTimeout(() => {
            setVisible(props.visible);
        }, 100);
    }, [props.visible])

    const loadData = () => {
        if (!componentLoading) {
            setComponentLoading(true);
        }

        HTTP.get(Routes.api.admin.loginCredentials)
        .then(response => {
            Utils.handleSuccessResponse(response, () => {
                form.setFieldsValue({
                    id: response.data.payload.id,
                    email: response.data.payload.email,
                });
            })
        })
        .catch((error) => {
            Utils.handleException(error);
        }).finally(() => {
            setComponentLoading(false);
        });
    }

    const handleClose = () => {
        setVisible(false);
        setTimeout(() => {
            props.handleCancel();
        }, 400);
    };

    const handleOk = () => {
        form
        .validateFields()
        .then((values) => {
            //save form
            setLoading(true);
            
            HTTP.post(Routes.api.admin.loginCredentials, {
                id: values.id,
                email: values.email,
                password: values.password
            })
            .then(response => {
                Utils.handleSuccessResponse(response, () => {
                    form.resetFields();
                    Utils.showNotification( response.data.message, 'success', false);
                    handleClose();
                })
            })
            .catch((error) => {
                Utils.handleException(error);
            }).finally(() => {
                setLoading(false);
            });
        })
        .catch((info) => {
            console.log('Validate Failed:', info);
        });
    }

    return (
        <StyledDrawer
            title="Login Credential"
            onClose={handleClose}
            visible={visible}
            destroyOnClose={true}
            maskClosable={false}
            forceRender={true}
            footer={
                <div
                    style={{
                        textAlign: 'right',
                    }}
                >
                    <Button disabled={componentLoading} onClick={handleClose} style={{ marginRight: 8 }}>
                        Cancel
                    </Button>
                    <Button disabled={componentLoading} onClick={handleOk} type="primary" loading={loading}>
                        Save
                    </Button>
                </div>
            }
        >
            <Spin spinning={componentLoading} size="large" delay={500}>
                <Form
                    preserve={false}
                    form={form}
                    layout="vertical"
                    name="login-credentials"
                >
                    <Form.Item name="id" hidden>
                        <Input/>
                    </Form.Item>
                    <Form.Item
                        name="email"
                        label="Email"
                        rules={[
                            {
                                required: true,
                                message: 'Please enter your email'
                            },
                            {
                                type: 'email',
                                message: 'Invalid email address'
                            }
                        ]}
                    >
                        <Input placeholder="Email"/>
                    </Form.Item>
                    <Form.Item
                        name="password"
                        label="Password"
                        rules={[
                            {
                                required: true,
                                message: 'Please enter your password'
                            },
                            {
                                min: 5,
                                message: 'The password is too short'
                            },
                        ]}
                        hasFeedback
                    >
                        <Input.Password placeholder="New Password"/>
                    </Form.Item>
                    <Form.Item
                        name="confirmPassword"
                        label="Confirm Password"
                        dependencies={['password']}
                        rules={[
                            {
                                required: true,
                                message: 'Please confirm your password',
                            },
                            ({ getFieldValue }) => ({
                                validator(rule, value) {
                                if (!value || getFieldValue('password') === value) {
                                    return Promise.resolve();
                                }
                                return Promise.reject('Confirm password does not match');
                                },
                            }),
                        ]}
                        hasFeedback
                    >
                        <Input.Password placeholder="Confirm Password"/>
                    </Form.Item>
                </Form>
            </Spin>
        </StyledDrawer>
    )
}

LoginCredentialPopup.propTypes = {
    visible: PropTypes.bool.isRequired,
    handleCancel: PropTypes.func.isRequired,
}

export default LoginCredentialPopup;