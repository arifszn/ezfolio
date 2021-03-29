import React, { useEffect, useState } from 'react';
import { Modal, Form, Input, message, Switch, Spin } from 'antd';
import { BlockPicker } from 'react-color';

const ColorPickerPopup = (props) => {
    const [visible, setVisible] = useState(props.visible);
    const [color, setColor] = useState(props.selectedColor);

    useEffect(() => {
        setVisible(props.visible);
    }, [props.visible])

    useEffect(() => {
        setColor(props.selectedColor);
    }, [props.selectedColor])
    
    const handleOk = () => {
        props.submitCallback(color);
        handleCancel();
    }

    const handleCancel = () => {
        setVisible(false);
        setTimeout(() => {
            props.handleCancel();
        }, 800);
    }

    return (
        <React.Fragment>
            <Modal
                visible={visible}
                closable={false}
                onOk={handleOk}
                onCancel={handleCancel}
                destroyOnClose={true}
                maskClosable={false}
                okText="Save"
                forceRender={true}
                width={'max-content'}
            >
                <BlockPicker
                    color={color}
                    triangle="hide"
                    onChange={(color, event) => {
                        props.colorPickerOnChange(color);
                    }}
                    colors={[
                        '#00C853',
                        '#64DD17',
                        '#AEEA00',
                        '#FFD600',
                        '#d0021b',
                        '#C51162',
                        '#AA00FF',
                        '#673ab7',
                        '#8ed1fc',
                        '#f78da7',
                        '#6200EA',
                        '#795548',
                        '#2962FF',
                        '#0091EA',
                        '#00B8D4',
                        '#81c784',
                        '#FFAB00',
                        '#FF6D00',
                        '#555555',
                        '#000000'
                    ]}
                />
            </Modal>
        </React.Fragment>
    )
}

export default ColorPickerPopup;