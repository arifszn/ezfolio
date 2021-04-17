import { Button, Space, Form, Input } from 'antd';
import React, { useEffect, useState } from 'react';
import AuthLayout from './AuthLayout';
import axios from 'axios';
import Routes from '../../../common/helpers/Routes';
import Utils from '../../../common/helpers/Utils';
import { Link, useHistory, useLocation, useParams } from 'react-router-dom';
import { useSelector } from 'react-redux';
import { AiOutlineMail } from 'react-icons/ai';
import {BsShieldLock} from 'react-icons/bs';

const ResetPassword = () => {
    const { token } = useParams();
    const [loading, setLoading] = useState(false);
    const globalState = useSelector(state => state.globalState);
    const siteName = globalState.siteName;
    const apiToken = globalState.apiToken;
    let history = useHistory();
    let location = useLocation();

    useEffect(() => {
        document.title = `Reset Password - ${siteName}`;
    }, [])

    useEffect(() => {
        if (apiToken) {
            let { from } = location.state || { from: { pathname: Routes.web.admin.dashboard } };
            history.push(from);
        }
    }, [apiToken]);

    const onSubmit = (values) => {
        setLoading(true);
        
        axios.post(Routes.api.admin.resetPassword, {
            email: values.email,
            password: values.password,
            password_confirmation: values.confirmPassword,
            token: token,
        })
        .then(response => {
            Utils.handleSuccessResponse(response, () => {
                Utils.showNotification(response.data.message, 'success');
                history.push(Routes.web.admin.login);
            });
        })
        .catch((error) => {
            Utils.handleException(error);
        }).finally(() => {
            setLoading(false);
        });
    };

    return (
        <React.Fragment>
            <AuthLayout title={'RESET PASSWORD'}>
                <Form
                    name="resetPassword"
                    onFinish={onSubmit}
                >
                    <Form.Item
                        name="email"
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
                        <Input prefix={<AiOutlineMail />} placeholder="Email" />
                    </Form.Item>

                    <Form.Item
                        name="password"
                        rules={[
                            {
                                required: true,
                                message: 'Please enter your new password'
                            },
                            {
                                min: 5,
                                message: 'The password is too short'
                            },
                        ]}
                        hasFeedback
                    >
                        <Input.Password prefix={<BsShieldLock />} placeholder="New Password"/>
                    </Form.Item>

                    <Form.Item
                        name="confirmPassword"
                        dependencies={['password']}
                        rules={[
                            {
                                required: true,
                                message: 'Please enter your new password',
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
                        <Input.Password prefix={<BsShieldLock />} placeholder="Confirm Password"/>
                    </Form.Item>

                    <Form.Item>
                        <Space direction="vertical" style={{width: '100%'}}>
                            <Button type="primary" htmlType="submit" block loading={loading}>
                                Reset Password
                            </Button>
                            
                            <Button type="default" htmlType="button" block disabled={loading}>
                                <Link to={Routes.web.admin.login}>Back to Login</Link>
                            </Button>
                        </Space>
                    </Form.Item>
                </Form>
            </AuthLayout>
        </React.Fragment>
    )
}

export default ResetPassword;