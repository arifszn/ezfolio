import { Card } from "antd"
import React from "react";
import PropTypes from 'prop-types';

const PageWrapper = ({children, className = '', loading = false}) => {
    return (
        <React.Fragment>
            <Card bordered={false} bodyStyle={{padding: 0}} hoverable className={`${className} z-shadow`} loading={loading}>
                {children}
            </Card>
        </React.Fragment>
    )
}

PageWrapper.propTypes = {
    children: PropTypes.node,
    className: PropTypes.string,
    loading: PropTypes.bool
}

export default PageWrapper;