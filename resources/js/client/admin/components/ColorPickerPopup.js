import React, { useEffect, useState } from 'react';
import { Modal } from 'antd';
import { BlockPicker } from 'react-color';
import PropTypes from 'prop-types';

const ColorPickerPopup = (props) => {
    const [visible, setVisible] = useState(props.visible);
    const [color, setColor] = useState(props.selectedColor);
    const [previousColor, setPreviousColor] = useState(null);

    useEffect(() => {
        setPreviousColor(props.selectedColor);
    }, [])

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

    const handleCancel = (_previousColor = null) => {
        setVisible(false);
        setTimeout(() => {
            props.handleCancel(_previousColor);
        }, 400);
    }

    return (
        <React.Fragment>
            <Modal
                visible={visible}
                closable={false}
                onOk={handleOk}
                onCancel={() => {handleCancel(previousColor)}}
                destroyOnClose={true}
                maskClosable={false}
                okText="Save"
                forceRender={true}
                width={'max-content'}
            >
                <BlockPicker
                    color={color}
                    triangle="hide"
                    onChange={(color) => {
                        props.colorPickerOnChange(color);
                    }}
                    colors={[
                        '#1890ff',
                        '#2962FF',
                        '#0091EA',
                        '#00B8D4',
                        '#673ab7',
                        '#AA00FF',
                        '#00C853',
                        '#64DD17',
                        '#AEEA00',
                        '#81c784',
                        '#d0021b',
                        '#C51162',
                        '#f78da7',
                        '#FFD600',
                        '#FFAB00',
                        '#FF6D00',
                        '#abb8c3',
                        '#555555',
                        '#795548',
                        '#000000'
                    ]}
                />
            </Modal>
        </React.Fragment>
    )
}

ColorPickerPopup.propTypes = {
    colorPickerOnChange: PropTypes.func.isRequired,
    handleCancel: PropTypes.func.isRequired,
    submitCallback: PropTypes.func.isRequired,
    visible: PropTypes.bool.isRequired,
    selectedColor: PropTypes.string.isRequired,
}

export default ColorPickerPopup;