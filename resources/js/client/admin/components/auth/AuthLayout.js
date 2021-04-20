import { Card, Col, Layout, Row, Typography } from 'antd';
import React from 'react';
import styled, { keyframes, css } from 'styled-components';
import Utils from '../../../common/helpers/Utils';
import { useSelector } from 'react-redux';
import PropTypes from 'prop-types';

const zoomInAnimation = keyframes`
0% {
-webkit-transform: scale(0.5);
        transform: scale(0.5);
}
100% {
-webkit-transform: scale(1);
        transform: scale(1);
}
`;

const StyledContent = styled(Layout.Content)`
    min-height: 100vh !important;
    display: flex;
    justify-content: center;
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
    margin: 28px !important;
    animation: ${css`${zoomInAnimation} 0.4s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;`};
`;

const AuthLayout = ({ children, title }) => {
    const logo = useSelector(state => state.globalState.logo);

    return (
        <React.Fragment>
            <Layout>
                <StyledContent>
                    <Row
                        justify="center"
                        align="middle"
                    >
                        <Col>
                            <Wrapper>
                                <Card
                                    hoverable={true}
                                    bordered={false}
                                    className="z-shadow"
                                    style={{maxWidth: 380, cursor: 'default'}}
                                    bodyStyle={{padding: '40px 40px 40px 40px'}}
                                >
                                    <Row>
                                        <Col span={24}>
                                            <StyledTitle level={3} style={{textAlign: 'center'}}>
                                                <img src={Utils.backend + '/' + logo} alt='logo'/>
                                            </StyledTitle>
                                        </Col>
                                        <Col span={24}><Title>{title}</Title></Col>
                                        <Col span={24}>
                                            {children}
                                        </Col>
                                    </Row>
                                </Card>
                            </Wrapper>
                        </Col>
                    </Row>
                </StyledContent>
            </Layout>
        </React.Fragment>
    )
}

AuthLayout.propTypes = {
    children: PropTypes.node,
    title: PropTypes.string
}

export default AuthLayout;