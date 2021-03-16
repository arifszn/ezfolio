import { Avatar, Button, Card, Col, Divider, Form, Input, Layout, Row, Typography } from 'antd';
import React, { useState } from 'react';
import styled from 'styled-components';
import { UserOutlined, LockOutlined, AntDesignOutlined } from '@ant-design/icons';
import { Link } from 'react-router-dom';
import Utils from '../../../common/helpers/Utils';
import { useSelector } from 'react-redux';

const StyledContent = styled(Layout.Content)`
    min-height: 100vh;
    display: table;
`;

const StyledRow = styled(Row)`
    display: table-cell;
    vertical-align: middle;
`;

const Title = styled.h4`
    text-align: center;
    color: lightgray;
    margin-bottom: 30px;
`;

const StyledTitle = styled(Typography.Title)`
    text-align: center;
    padding-bottom: 30px;
    padding-top: 10px;

    img {
        max-height: 58px;
    }
`;

const Wrapper = styled.div`
    margin: 28px;
`;

const StyledCard = styled(Card)`
    max-width: 380px; 
    margin: 0 auto;
    .ant-card-body {
        padding: 40px 40px 40px 40px;
    }
`;

const StyledAvatar = styled(Avatar)`
    text-align: center;
`;

const Login = () => {
    const [loading, setLoading] = useState(false);
    const logo = useSelector(state => state.globalState.logo);

    const onSubmit = (values) => {
        
    };

    return (
        <React.Fragment>
            <Layout>
                <StyledContent>
                    <StyledRow 
                        justify="space-around" 
                        align="middle"
                    >
                        <Col span={24}>
                            <Wrapper>
                                <StyledCard
                                    hoverable={true}
                                    bordered={false}
                                    className="z-shadow"
                                >
                                    <Row>
                                        <Col span={24}>
                                            <StyledTitle level={3} style={{textAlign: 'center'}}>
                                                <img src={Utils.backend + '/' + logo} alt='logo'/>
                                            </StyledTitle>
                                        </Col>
                                        <Col span={24}><Title>ADMIN LOGIN</Title></Col>
                                        <Col span={24}>
                                            <Form
                                                name="login"
                                                initialValues={{ remember: true }}
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
                                                    <Input prefix={<UserOutlined/>} placeholder="Email" />
                                                </Form.Item>
                                                <Form.Item
                                                    name="password"
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
                                                    <a to={'#'}>
                                                        <Typography.Text type="secondary">Forgot password?</Typography.Text>
                                                    </a>
                                                </Form.Item>
                                            </Form>
                                        </Col>
                                    </Row>
                                </StyledCard>
                            </Wrapper>
                        </Col>
                    </StyledRow>
                </StyledContent>
            </Layout>
        </React.Fragment>
    )
}

export default Login;