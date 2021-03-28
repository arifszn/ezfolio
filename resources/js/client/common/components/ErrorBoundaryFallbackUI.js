import { Result } from 'antd';
import React from 'react';
import styled from 'styled-components';

// Create an ErrorTitle component that'll render an <h1> tag with some styles
const ErrorTitle = styled.h1`
    font-size: 1.5em;
    color: palevioletred;
`;

// Create an ErrorSubTitle component that'll render an <small> tag with some styles
const ErrorSubTitle = styled.small`
    color: grey;
`;

// Create a ErrorWrapper component that'll render an <section> tag with some styles
const ErrorWrapper = styled.section`
    text-align: center;
    padding: 4em;
    background: papayawhip;
`;

const Wrapper = styled.section`
    margin-top: 30px;
`;

/**
 * Simple Error boundary fallback UI to display when error occurs
 */
export const SimpleErrorBoundaryFallbackUI = () => {
    return (
        <ErrorWrapper>
            <ErrorTitle>
                <code>Something went wrong!</code>
            </ErrorTitle>
            
            {
                // eslint-disable-next-line no-undef
                (!process.env.NODE_ENV || process.env.NODE_ENV === 'development') &&
                <ErrorSubTitle>
                    <code>Open <strong>console</strong> for more details.</code>
                </ErrorSubTitle>
            }
        </ErrorWrapper>
    )
}

/**
 * Error boundary fallback UI to display when error occurs
 */
export const ErrorBoundaryFallbackUI = () => {
    return (
        <Wrapper>
            <Result
                status={500}
                title="Something went wrong!"
                // eslint-disable-next-line no-undef
                subTitle={(!process.env.NODE_ENV || process.env.NODE_ENV === 'development') ? 
                    <code>Open <strong>console</strong> for more details.</code> :
                    ''
                }
            />
        </Wrapper>
    )
}