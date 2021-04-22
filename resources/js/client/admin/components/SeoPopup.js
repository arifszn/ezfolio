import React, { useEffect, useState } from 'react';
import { Drawer, Input, Button, Form } from 'antd';
import styled from 'styled-components';
import PropTypes from 'prop-types';
import FileUploaderFormInput from './uploader/FileUploaderFormInput';
import Utils from '../../common/helpers/Utils';
import HTTP from '../../common/helpers/HTTP';
import Routes from '../../common/helpers/Routes';

const StyledDrawer = styled(Drawer)`
    .ant-drawer-content-wrapper {
        width: 520px !important;
        @media (max-width: 768px) {
            max-width: calc(100vw - 16px) !important;
        }
    }
`;

const SeoPopup = (props) => {
    const [visible, setVisible] = useState(false);
    const [loading, setLoading] = useState(false);
    const [form] = Form.useForm();

    useEffect(() => {
        if (props.data) {
            form.setFieldsValue({
                title: props.data.title, 
                author: props.data.author,
                description: props.data.description,
                image: props.data.image,
            });
        }
    }, [props.data])

    useEffect(() => {
        setTimeout(() => {
            setVisible(props.visible);
        }, 100);
    }, [props.visible])

    const imageOnChange = (files) => {
        form.setFieldsValue({
            image: files.length ? files[0] : ''
        });
    }

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
            //save form
            setLoading(true);

            const formData = new FormData();
            
            formData.append('title', values.title);
            formData.append('author', values.author);
            formData.append('description', values.description);
            formData.append('image', values.image);

            HTTP.post(Routes.api.admin.seo, formData)
            .then(response => {
                Utils.handleSuccessResponse(response, () => {
                    Utils.showNotification(response.data.message, 'success');
                    props.submitCallback(response.data.payload.inserted);
                })
            })
            .catch((error) => {
                Utils.handleException(error);
            }).finally(() => {
                setLoading(false);
            });
        })
        .catch((info) => {
            console.log('Validate Failed:', info);
        });
    }

    return (
        <StyledDrawer
            title="Search Engine Optimization"
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
                    <Button  onClick={handleClose} style={{ marginRight: 8 }}>
                        Cancel
                    </Button>
                    <Button onClick={handleOk} type="primary" loading={loading}>
                        Save
                    </Button>
                </div>
            }
        >
            <Form
                preserve={false}
                form={form}
                layout="vertical"
                name="seo"
            >
                <Form.Item
                    label={<React.Fragment>Meta Title</React.Fragment>}
                    name="title"
                >
                    <Input placeholder="Enter Meta Title"/>
                </Form.Item>
                <Form.Item
                    label={<React.Fragment>Meta Author</React.Fragment>}
                    name="author"
                >
                    <Input placeholder="Enter Meta Author"/>
                </Form.Item>
                <Form.Item
                    label={<React.Fragment>Meta Description</React.Fragment>}
                    name="description"
                >
                    <Input.TextArea rows={4} placeholder="Enter Meta Description"/>
                </Form.Item>
                <Form.Item 
                    label="Meta Image" 
                    name="image"
                >
                    <FileUploaderFormInput
                        previewFile={props.data && props.data.image && (Utils.backend + '/' + props.data.image)}
                        onChangeCallback={imageOnChange}
                        acceptedFileTypes={"image/*"}
                        labelIdle={'Drag & Drop your image or <span class="filepond--label-action">Browse</span>'}
                    />
                </Form.Item>
            </Form>
        </StyledDrawer>
    )
}

SeoPopup.propTypes = {
    handleCancel: PropTypes.func.isRequired,
}

SeoPopup.propTypes = {
    data: PropTypes.object,
    visible: PropTypes.bool.isRequired,
    submitCallback: PropTypes.func
}

export default SeoPopup;