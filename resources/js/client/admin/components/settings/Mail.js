import { PageHeader, Form, Spin, Input, Select, Button, Typography } from 'antd';
import React, { useEffect, useState } from 'react';
import { InfoCircleOutlined } from '@ant-design/icons';
import HTTP from '../../../common/helpers/HTTP';
import Routes from '../../../common/helpers/Routes';
import Utils from '../../../common/helpers/Utils';

const { Option } = Select;

const Mail = () => {
    const [loading, setLoading] = useState(false);
    const [componentLoading, setComponentLoading] = useState(false);
    const [form] = Form.useForm();

    useEffect(() => {
        loadMailSetting();
    }, [])

    const loadMailSetting = (_componentLoading = true) => {
        setComponentLoading(_componentLoading);

        HTTP.get(Routes.api.admin.settings)
        .then(response => {
            Utils.handleSuccessResponse(response, () => {
                form.setFieldsValue({
                    MAIL_MAILER: response.data.payload.mailSettings.MAIL_MAILER,
                    MAIL_HOST: response.data.payload.mailSettings.MAIL_HOST,
                    MAIL_PORT: response.data.payload.mailSettings.MAIL_PORT,
                    MAIL_USERNAME: response.data.payload.mailSettings.MAIL_USERNAME,
                    MAIL_PASSWORD: response.data.payload.mailSettings.MAIL_PASSWORD,
                    MAIL_ENCRYPTION: response.data.payload.mailSettings.MAIL_ENCRYPTION,
                    MAIL_FROM_ADDRESS: response.data.payload.mailSettings.MAIL_FROM_ADDRESS,
                    MAIL_FROM_NAME: response.data.payload.mailSettings.MAIL_FROM_NAME,
                });
            })
        })
        .catch((error) => {
            Utils.handleException(error);
        }).finally(() => {
            setComponentLoading(false);
        });
    }

    const onFinish = (values) => {
        if (!loading) {
            setLoading(true);
        }

        HTTP.post(Routes.api.admin.mailSettings, {
            MAIL_MAILER: values.MAIL_MAILER,
            MAIL_HOST: values.MAIL_HOST,
            MAIL_PORT: values.MAIL_PORT,
            MAIL_USERNAME: values.MAIL_USERNAME,
            MAIL_PASSWORD: values.MAIL_PASSWORD,
            MAIL_ENCRYPTION: values.MAIL_ENCRYPTION,
            MAIL_FROM_ADDRESS: values.MAIL_FROM_ADDRESS,
            MAIL_FROM_NAME: values.MAIL_FROM_NAME,
        })
        .then(response => {
            Utils.handleSuccessResponse(response, () => {
                Utils.showNotification(response.data.message, 'success');
            })
        })
        .catch((error) => {
            Utils.handleException(error);
        }).finally(() => {
            setLoading(false);
        });
    };

    const onFinishFailed = errorInfo => {
        console.log('Failed:', errorInfo);
    };

    return (
        <React.Fragment>
            <PageHeader
                title="Mail Settings"
                subTitle={
                    <Typography.Text
                        style={{ width: '100%', color: 'grey' }}
                        ellipsis={{ tooltip: 'Optional and needed for password recovery mail' }}
                    >
                        Optional and needed for password recovery mail
                    </Typography.Text>
                }
            >
                <Spin spinning={componentLoading} delay={500} size="large">
                    <Form
                        layout="vertical"
                        name="mail-setting"
                        form={form}
                        onFinish={onFinish}
                        onFinishFailed={onFinishFailed}
                    >
                        <Form.Item
                            name="MAIL_MAILER"
                            label="Mail Driver"
                            rules={[
                                {
                                    required: true,
                                    message: 'Mail Driver is required'
                                },
                            ]}
                        >
                            <Input placeholder="Enter Mail Driver"/>
                        </Form.Item>
                        <Form.Item
                            name="MAIL_HOST"
                            label="Mail Host"
                            rules={[
                                {
                                    required: true,
                                    message: 'Mail Host is required'
                                },
                            ]}
                        >
                            <Input placeholder="Enter Mail Host"/>
                        </Form.Item>
                        <Form.Item
                            name="MAIL_PORT"
                            label="Mail Port"
                            rules={[
                                {
                                    required: true,
                                    message: 'Mail Port is required'
                                }
                            ]}
                        >
                            <Input placeholder="Enter Mail Port"/>
                        </Form.Item>
                        <Form.Item
                            name="MAIL_USERNAME"
                            label="Mail Username"
                            rules={[
                                {
                                    required: true,
                                    message: 'Mail Username is required'
                                },
                            ]}
                        >
                            <Input placeholder="Enter Mail Username"/>
                        </Form.Item>
                        <Form.Item
                            name="MAIL_PASSWORD"
                            label="Mail Password"
                            rules={[
                                {
                                    required: true,
                                    message: 'Mail Password is required'
                                },
                            ]}
                        >
                            <Input.Password placeholder="Enter Mail Password"/>
                        </Form.Item>
                        <Form.Item
                            name="MAIL_ENCRYPTION"
                            label="Mail Encryption"
                            rules={[
                                {
                                    required: true,
                                    message: 'Mail Encryption is required'
                                },
                            ]}
                        >
                           <Select placeholder="Select Mail Encryption">
                                <Option value="tls">TLS</Option>
                                <Option value="ssl">SSL</Option>
                            </Select>
                        </Form.Item>
                        <Form.Item
                            name="MAIL_FROM_ADDRESS"
                            label="Sender Email"
                            rules={[
                                {
                                    type: 'email',
                                    message: 'Mail From Email is not a valid email'
                                },
                            ]}
                            tooltip={{ title: 'Sender email to be displayed on the mail', icon: <InfoCircleOutlined /> }}
                        >
                            <Input placeholder="Sender Email"/>
                        </Form.Item>
                        <Form.Item
                            name="MAIL_FROM_NAME"
                            label="Sender Name"
                            rules={[
                                
                            ]}
                            tooltip={{ title: 'Sender name to be displayed on the mail', icon: <InfoCircleOutlined /> }}
                        >
                            <Input placeholder="Sender Name"/>
                        </Form.Item>
                        <Form.Item 
                            style={{
                                textAlign: 'right',
                            }}
                        >
                            <Button loading={loading} type="primary" htmlType="submit">
                                Save Settings
                            </Button>
                        </Form.Item>
                    </Form>
                </Spin>
            </PageHeader>
        </React.Fragment>
    );
};

export default Mail;