import { Card, PageHeader } from 'antd';
import React from 'react';
import styled from 'styled-components';

const StyledCard = styled(Card)`
.ant-card-body {
    padding: 0;
}
`;

const PortfolioConfig = () => {
    return (
        <React.Fragment>
            <StyledCard className="z-shadow" hoverable={true}>
                <PageHeader
                    title="Basic Settings"
                    className="site-page-header"
                    subTitle="This is a subtitle"
                    
                >
                    fsdfsd
                </PageHeader>
            </StyledCard>
        </React.Fragment>
    )
}

export default PortfolioConfig;