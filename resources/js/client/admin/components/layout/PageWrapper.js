import { Card, Skeleton } from "antd"
import React from "react";
import PropTypes from 'prop-types';

const PageWrapper = ({children, className = '', loading = false, loadingRows = 10}) => {
    return (
        <React.Fragment>
            <Card bordered={false} bodyStyle={loading ? {} : {padding: 0}} hoverable className={`${className} z-shadow`} style={{cursor: 'default'}}>
                <Skeleton loading={loading} title={false} active paragraph={{ rows: loadingRows, width: '100%' }}>
                    {children}
                </Skeleton>
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