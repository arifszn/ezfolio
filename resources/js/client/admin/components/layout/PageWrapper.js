import { Card } from "antd"
import React from "react";
import PropTypes from 'prop-types';

const PageWrapper = ({children, className = '', loading = false}) => {
    return (
        <React.Fragment>
            <Card 
                bordered={false} 
                bodyStyle={{padding: 0}} 
                hoverable 
                className={`${className} z-shadow`} 
                style={{cursor: 'default'}} 
                loading={loading}
            >
                {children}
            </Card>
        </React.Fragment>
    )
}

PageWrapper.propTypes = {
    children: PropTypes.node,
    className: PropTypes.string,
    loading: PropTypes.bool,
    loadingRows: PropTypes.number
}

export default PageWrapper;