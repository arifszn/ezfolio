import React, { useEffect, useState } from 'react';
import { Drawer, Button, Spin, Input, Form } from 'antd';
import styled from 'styled-components';
import PropTypes from 'prop-types';

const StyledDrawer = styled(Drawer)`
    .ant-drawer-content-wrapper {
        width: 520px !important;
        @media (max-width: 768px) {
            max-width: calc(100vw - 16px) !important;
        }
    }
`;

const SocialLinkPopup = (props) => {
    const [visible, setVisible] = useState(false);
    const [form] = Form.useForm();
    const [loading, setLoading] = useState((typeof props.loading !== 'undefined') ? props.loading : false);
    const [componentLoading, setComponentLoading] = useState((typeof props.componentLoading !== 'undefined') ? props.componentLoading : false);

    useEffect(() => {
        if (typeof props.socialLink !== 'undefined' && props.socialLink) {
            form.setFieldsValue({
                index: props.socialLink.index,
                title: props.socialLink.data.title,
                iconClass: props.socialLink.data.iconClass,
                link: props.socialLink.data.link,
            });
        }
    }, [props.socialLink])

    useEffect(() => {
        setTimeout(() => {
            setVisible(props.visible);
        }, 100);
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
        }, 400);
    };

    const handleOk = () => {
        form
        .validateFields()
        .then((values) => {
            props.submitCallback({
                index: values.index ? values.index : null,
                title: values.title,
                iconClass: values.iconClass,
                link: values.link,
            })
        })
        .catch((info) => {
            console.log('Validate Failed:', info);
        });
    }

    return (
        <StyledDrawer
            title={props.title}
            onClose={handleClose}
            visible={visible}
            destroyOnClose={true}
            maskClosable={false}
            forceRender={true}
            footer={
                <div
                    style={{
                        textAlign: 'right',
                    }}
                >
                    <Button disabled={componentLoading} onClick={handleClose} style={{ marginRight: 8 }}>
                        Cancel
                    </Button>
                    <Button disabled={componentLoading} onClick={handleOk} type="primary" loading={loading}>
                        Save
                    </Button>
                </div>
            }
        >
            <Spin spinning={componentLoading} size="large" delay={500}>
                <Form
                    preserve={false}
                    form={form}
                    layout="vertical"
                    name="social-link"
                >
                    <Form.Item name="index" hidden>
                        <Input/>
                    </Form.Item>
                    <Form.Item
                        name="title"
                        label="Title"
                        rules={[
                            {
                                required: true,
                                message: 'Please enter title'
                            },
                        ]}
                    >
                        <Input placeholder="Enter Title"/>
                    </Form.Item>
                    <Form.Item
                        name="iconClass"
                        label="Icon Class"
                        extra={<React.Fragment>Find your suitable icon: <a href="https://fontawesome.com/icons" target="_blank" rel="noreferrer">Font Awesome</a>|<a href="http://code.meta-platform.com/assets/mdi/preview.html" target="_blank" rel="noreferrer">Material Design</a></React.Fragment>}
                        rules={[
                            {
                                required: true,
                                message: 'Please enter icon class'
                            },
                        ]}
                    >
                        <Input placeholder="Enter Icon Class"/>
                    </Form.Item>
                    <Form.Item
                        name="link"
                        label="Link"
                        rules={[
                            {
                                required: true,
                                message: 'Please enter link'
                            },
                            {
                                type: 'url',
                                message: 'Invalid url'
                            },
                        ]}
                    >
                        <Input placeholder="Enter Link"/>
                    </Form.Item>
                </Form>
            </Spin>
        </StyledDrawer>
    )
}

SocialLinkPopup.propTypes = {
    handleCancel: PropTypes.func.isRequired,
    submitCallback: PropTypes.func.isRequired,
    visible: PropTypes.bool.isRequired,
    socialLink: PropTypes.object,
    loading: PropTypes.bool,
    componentLoading: PropTypes.bool,
    title: PropTypes.node,
}

export default SocialLinkPopup;