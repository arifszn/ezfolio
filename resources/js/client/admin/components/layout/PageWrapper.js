import { Card, Skeleton } from "antd"
import React from "react";
import PropTypes from 'prop-types';

const PageWrapper = ({children, className = '', loading = false, loadingRows = 8}) => {
    return (
        <React.Fragment>
            <Card bordered={false} bodyStyle={loading ? {} : {padding: 0}} hoverable className={`${className} z-shadow`} >
                <Skeleton loading={loading} active paragraph={{ rows: loadingRows }}>
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