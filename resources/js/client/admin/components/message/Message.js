import React, { useEffect, useState } from 'react';
import { Drawer, Button, Spin, Divider, Row, Col } from 'antd';
import styled from 'styled-components';
import PropTypes from 'prop-types';
import moment from 'moment';

const StyledDrawer = styled(Drawer)`
    .ant-drawer-content-wrapper {
        width: 520px !important;
        @media (max-width: 768px) {
            max-width: calc(100vw - 16px) !important;
        }
    }
`;

const StyledTitle = styled.p`
    display: block;
    margin-bottom: 16px;
    color: rgba(0, 0, 0, 0.85);
    font-size: 16px;
    line-height: 1.5715;
    margin-bottom: 16px;
`;

const Message = (props) => {
    const [visible, setVisible] = useState(false);
    const [componentLoading, setComponentLoading] = useState((typeof props.componentLoading !== 'undefined') ? props.componentLoading : false);

    useEffect(() => {
        setTimeout(() => {
            setVisible(props.visible);
        }, 100);
    }, [props.visible])

    useEffect(() => {
        if (typeof props.componentLoading !== 'undefined') {
            setComponentLoading(props.componentLoading)
        }
    }, [props.componentLoading])

    const handleClose = () => {
        setVisible(false);
        setTimeout(() => {
            props.handleCancel();
        }, 400);
    };

    return (
        <StyledDrawer
            title={props.title}
            onClose={handleClose}
            visible={visible}
            destroyOnClose={true}
            maskClosable={true}
            forceRender={true}
            footer={
                <div
                    style={{
                        textAlign: 'right',
                    }}
                >
                    <Button disabled={componentLoading} onClick={handleClose} style={{ marginRight: 8 }}>
                        Close
                    </Button>
                </div>
            }
        >
            <Spin spinning={componentLoading} size="large" delay={500}>
                <StyledTitle>Sender Name</StyledTitle>
                <Row>
                    <Col span={24}>
                        {props.data && props.data.name}
                    </Col>
                </Row>
                <Divider />
                <StyledTitle>Sender Email</StyledTitle>
                <Row>
                    <Col span={24}>
                        {props.data && props.data.email}
                    </Col>
                </Row>
                <Divider />
                <StyledTitle>Subject</StyledTitle>
                <Row>
                    <Col span={24}>
                        {props.data && props.data.subject}
                    </Col>
                </Row>
                <Divider/>
                <StyledTitle>Body</StyledTitle>
                <Row>
                    <Col span={24}>
                        {props.data && props.data.body}
                    </Col>
                </Row>
                <Divider/>
                <StyledTitle>Received</StyledTitle>
                <Row>
                    <Col span={24}>
                        {props.data && moment(props.data.created_at).fromNow()}
                    </Col>
                </Row>
            </Spin>
        </StyledDrawer>
    )
}

Message.propTypes = {
    handleCancel: PropTypes.func.isRequired,
    visible: PropTypes.bool.isRequired,
    data: PropTypes.object,
    componentLoading: PropTypes.bool,
    title: PropTypes.node,
}

export default Message;