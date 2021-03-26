import { Button, Space, Form, Input } from 'antd';
import React, { useEffect, useState } from 'react';
import { MailOutlined } from '@ant-design/icons';
import AuthLayout from './AuthLayout';
import axios from 'axios';
import Routes from '../../../common/helpers/Routes';
import Utils from '../../../common/helpers/Utils';
import { Link, useHistory } from 'react-router-dom';
import { useSelector } from 'react-redux';

const ForgetPassword = () => {
    const [loading, setLoading] = useState(false);
    const globalState = useSelector(state => state.globalState);
    const siteName = globalState.siteName;
    const apiToken = globalState.apiToken;
    let history = useHistory();

    useEffect(() => {
        document.title = `Forget Password - ${siteName}`;
    }, [])

    useEffect(() => {
        if (apiToken) {
            let { from } = location.state || { from: { pathname: Routes.web.admin.dashboard } };
            history.push(from);
        }
    }, [apiToken]);

    const onSubmit = (values) => {
        setLoading(true);
        
        axios.post(Routes.api.admin.forgetPassword, {
            email: values.email,
        })
        .then(response => {
            Utils.handleSuccessResponse(response, () => {
                Utils.showNotification(response.data.message, 'success', null, true);
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
            <AuthLayout title={'FORGET PASSWORD'}>
                <Form
                    name="forgetPassword"
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
                        <Input prefix={<MailOutlined />} placeholder="Email" />
                    </Form.Item>
                    <Form.Item>
                        <Space direction="vertical" style={{width: '100%'}}>
                            <Button type="primary" htmlType="submit" block loading={loading}>
                                Send Reset Email
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

export default ForgetPassword;