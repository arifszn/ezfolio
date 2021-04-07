import React, { useEffect, useState } from 'react';
import { Drawer, Button, Spin, Input } from 'antd';
import styled from 'styled-components';
import PropTypes from 'prop-types';

const StyledDrawer = styled(Drawer)`
    .ant-drawer-content-wrapper {
        width: 520px !important;
        @media (max-width: 767px) {
            max-width: calc(100vw - 16px) !important;
        }
    }
`;

const CodeEditorPopup = (props) => {
    const [visible, setVisible] = useState(true);
    const [value, setValue] = useState((typeof props.value !== 'undefined') ? props.value : '');
    const [loading, setLoading] = useState((typeof props.loading !== 'undefined') ? props.loading : false);
    const [componentLoading, setComponentLoading] = useState((typeof props.componentLoading !== 'undefined') ? props.componentLoading : false);

    useEffect(() => {
        if (typeof props.value !== 'undefined') {
            setValue(props.value)
        }
    }, [props.value])

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

    const handleOk = () => {
        props.submitCallback(value);
    }

    const onChange = ({ target: { value } }) => {
        setValue(value);
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
                <Input.TextArea
                    value={value}
                    onChange={onChange}
                    placeholder="console.log()"
                    autoSize={{ minRows: 15}}
                />
            </Spin>
        </StyledDrawer>
    )
}

CodeEditorPopup.propTypes = {
    handleCancel: PropTypes.func.isRequired,
    submitCallback: PropTypes.func.isRequired,
    value: PropTypes.string,
    loading: PropTypes.bool,
    componentLoading: PropTypes.bool,
    title: PropTypes.node,
}

export default CodeEditorPopup;