import React, { useEffect, useState } from 'react';
import { Drawer, Button, Spin, Input, Form, Divider, Carousel, Row, Col, Image } from 'antd';
import styled from 'styled-components';
import PropTypes from 'prop-types';
import Utils from '../../common/helpers/Utils';

const StyledDrawer = styled(Drawer)`
    .ant-drawer-content-wrapper {
        width: 520px !important;
        @media (max-width: 767px) {
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
    margin-bottom: 24px;
`;

const ProjectPopup = (props) => {
    const [visible, setVisible] = useState(false);
    const [loading, setLoading] = useState((typeof props.loading !== 'undefined') ? props.loading : false);
    const [componentLoading, setComponentLoading] = useState((typeof props.componentLoading !== 'undefined') ? props.componentLoading : false);

    useEffect(() => {
        setTimeout(() => {
            setVisible(props.visible);
        }, 400);
    }, [props.visible])

    useEffect(() => {
        if (typeof props.loading !== 'undefined') {
            setLoading(props.loading)
        }
    }, [props.loading])

    useEffect(() => {
        if (typeof props.componentLoading !== 'undefined') {
            setComponentLoading(props.componentLoading)
        }
    }, [props.componentLoading])

    const handleClose = () => {
        setVisible(false);
        setTimeout(() => {
            props.handleCancel();
        }, 800);
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
                <StyledTitle>Images</StyledTitle>
                <Row>
                    <Col span={24}>
                        <Carousel autoplay pauseOnHover={false}>
                            {
                                JSON.parse(props.project.images).map((image, index) => (
                                    <div>
                                        <Image
                                            src={Utils.backend + '/' + image}
                                            preview={false}
                                            placeholder={true}
                                            style={{
                                                maxHeight: '230px',
                                                transition: '0.3s ease',
                                                objectFit: 'cover'
                                            }}
                                        />
                                    </div>
                                ))
                            }
                        </Carousel>
                    </Col>
                </Row>
                <Divider />
                <StyledTitle>Images</StyledTitle>
                sdfsdf
            </Spin>
        </StyledDrawer>
    )
}

ProjectPopup.propTypes = {
    handleCancel: PropTypes.func.isRequired,
    visible: PropTypes.bool.isRequired,
    project: PropTypes.object,
    loading: PropTypes.bool,
    componentLoading: PropTypes.bool,
    title: PropTypes.node,
}

export default ProjectPopup;