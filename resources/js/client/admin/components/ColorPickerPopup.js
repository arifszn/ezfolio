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
        }, 800);
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

ColorPickerPopup.propTypes = {
    colorPickerOnChange: PropTypes.func.isRequired,
    handleCancel: PropTypes.func.isRequired,
    submitCallback: PropTypes.func.isRequired,
    visible: PropTypes.bool.isRequired,
    selectedColor: PropTypes.string.isRequired,
}

export default ColorPickerPopup;