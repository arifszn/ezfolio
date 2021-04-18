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
    display: table !important;
`;

const StyledRow = styled(Row)`
    display: table-cell !important;
    vertical-align: middle !important;
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

const StyledCard = styled(Card)`
    max-width: 380px !important; 
    margin: 0 auto !important;
    .ant-card-body {
        padding: 40px 40px 40px 40px !important;
    }
`;

const AuthLayout = ({ children, title }) => {
    const logo = useSelector(state => state.globalState.logo);

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
                                        <Col span={24}><Title>{title}</Title></Col>
                                        <Col span={24}>
                                            {children}
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

AuthLayout.propTypes = {
    children: PropTypes.node,
    title: PropTypes.string
}

export default AuthLayout;