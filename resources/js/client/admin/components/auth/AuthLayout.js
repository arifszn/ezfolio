import { Card, Col, Layout, Row, Typography } from 'antd';
import React from 'react';
import styled from 'styled-components';
import Utils from '../../../common/helpers/Utils';
import { useSelector } from 'react-redux';
import PropTypes from 'prop-types';

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