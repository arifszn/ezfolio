import React from 'react';
import { Result, Button } from 'antd';
import { Link } from 'react-router-dom';
import styled from 'styled-components';
import Routes from '../../common/helpers/Routes';

// Create a Wrapper component that'll render an <section> tag with some styles
const Wrapper = styled.section`
    margin-top: 30px;
`;

/**
 * Display not found page
 */
const NotFound = () => {
    return (
        <Wrapper>
            <Result
                status="404"
                title="404"
                subTitle="Sorry, the page you visited does not exist."
                extra={<Button type="primary"><Link to={Routes.web.admin.admin}>Back Home</Link></Button>}
            />
        </Wrapper>
    )
}

export default NotFound;