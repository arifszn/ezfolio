import { Button, Divider, Form, Input, Typography } from 'antd';
import React, { useEffect, useState } from 'react';
import { UserOutlined, LockOutlined } from '@ant-design/icons';
import AuthLayout from './AuthLayout';
import axios from 'axios';
import Routes from '../../../common/helpers/Routes';
import Utils from '../../../common/helpers/Utils';
import { Link } from 'react-router-dom';
import { useSelector } from 'react-redux';

const Login = () => {
    const [loading, setLoading] = useState(false);
    const siteName = useSelector(state => state.globalState.siteName);

    const onSubmit = (values) => {
        setLoading(true);
        
        axios.post(Routes.api.admin.login, {
            email: values.email,
            password: values.password,
        })
        .then(response => {
            Utils.handleSuccessResponse(response, () => {
                
            });
        })
        .catch((error) => {
            Utils.handleException(error);
        }).finally(() => {
            setLoading(false);
        });
    };

    useEffect(() => {
        document.title = `Login - ${siteName}`;
    }, [])

    return (
        <React.Fragment>
            <AuthLayout title={'ADMIN LOGIN'}>
                <Form
                    name="login"
                    onFinish={onSubmit}
                >
                    <Form.Item
                        name="email"
                        messageVariables={{ label: 'Email' }}
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
                        <Input prefix={<UserOutlined/>} placeholder="Email" />
                    </Form.Item>
                    <Form.Item
                        name="password"
                        messageVariables={{ label: 'Password' }}
                        rules={[
                            {
                                required: true,
                                message: 'Please enter your password'
                            }
                        ]}
                    >
                        <Input.Password
                            prefix={<LockOutlined/>}
                            placeholder="Password"
                        />
                    </Form.Item>
                    <Form.Item>
                        <Button type="primary" htmlType="submit" block loading={loading}>
                            Log in
                        </Button>
                    </Form.Item>
                    <Divider style={{marginBottom: '5px'}}/>
                    <Form.Item className="text-center">
                        <Link to={Routes.web.admin.forgetPassword}>
                            <Typography.Text type="secondary">Forgot password?</Typography.Text>
                        </Link>
                    </Form.Item>
                </Form>
            </AuthLayout>
        </React.Fragment>
    )
}

export default Login;