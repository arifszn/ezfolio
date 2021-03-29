import { PageHeader, Radio, Col, List, Card, Spin } from 'antd';
import React, { useEffect, useState } from 'react';
import { connect } from 'react-redux';
import { setGlobalState } from '../../redux/ActionCreators';
import { BlockPicker } from 'react-color';
import styled from 'styled-components';
import ColorPickerPopup from '../ColorPickerPopup';
import Utils from '../../../common/helpers/Utils';
import HTTP from '../../../common/helpers/HTTP';
import Routes from '../../../common/helpers/Routes';
import Constants from '../../../common/helpers/Constants';

const { Item } = List;

const StyledListItem = styled(Item)`
padding: 16px 0px !important;
`;

const Themes = (props) => {
    const [loading, setLoading] = useState(false);
    const [colorPickerVisible, setColorPickerVisible] = useState(false);
    const [currentSettingToChange, setCurrentSettingToChange] = useState(null);

    const colorPickerSubmitCallback = (color) => {
        submitData(Constants.settings.ACCENT_COLOR, color);
    }

    const colorPickerOnChange = (colorObject) => {
        const color = colorObject.hex;

        props.setGlobalState({
            accentColor: color
        });
        document.documentElement.style.setProperty('--z-accent-color', color);
        Utils.changeAccentColor(color);
    }

    const submitData = (name, value) => {
        if (!loading) {
            setLoading(true);
        }

        setCurrentSettingToChange(name);

        HTTP.post(Routes.api.admin.settings, {
            name: name,
            setting_value: value
        })
        .then(response => {
            Utils.handleSuccessResponse(response, () => {
                /* if (name === Constants.NAV_BAR_BACKGROUND) {
                    props.setGlobalState({
                        navbarBG: value
                    });
                    document.documentElement.style.setProperty('--z-navbar-bg', value);
                } else if (name === Constants.NAV_BAR_COLOR) {
                    props.setGlobalState({
                        navbarColor: value
                    });
                    document.documentElement.style.setProperty('--z-navbar-color', value);
                } else if (name === Constants.SIDE_BAR_BACKGROUND) {
                    props.setGlobalState({
                        sidebarBG: value
                    });
                    document.documentElement.style.setProperty('--z-sidebar-bg', value);
                } else if (name === Constants.SIDE_BAR_COLOR) {
                    props.setGlobalState({
                        sidebarColor: value
                    });
                    document.documentElement.style.setProperty('--z-sidebar-menu-color', value);
                } else if (name === Constants.SHORT_MENU) {
                    props.setGlobalState({
                        shortMenu: value
                    });
                    if (value) {
                        document.getElementById("body").classList.add("z-sidebar-icon-only");
                    } else {
                        document.getElementById("body").classList.remove("z-sidebar-icon-only");
                    }
                } */
            })
        })
        .catch(error => {
            Utils.handleException(error);
        })
        .finally(() => {
            setLoading(false);
        });
    }

    const menuLayoutChangeHandle = (e) => {
        const value = e.target.value;

        props.setGlobalState({
            menuLayout: value
        });
        submitData(Constants.settings.MENU_LAYOUT, value);
    } 

    return (
        <React.Fragment>
            <PageHeader
                title="Theme Settings"
            >
                <List
                    itemLayout="horizontal"
                    size="large"
                >
                    <Spin size="small" spinning={loading && currentSettingToChange === Constants.settings.ACCENT_COLOR}>
                        <StyledListItem actions={
                            [
                                <a 
                                    key="site-name-change" 
                                    onClick={() => {
                                        setColorPickerVisible(true);
                                    }}
                                >
                                    Change
                                </a>,
                            ]
                        }>
                            <Item.Meta title={'Accent Color'} description={'Change accent color of app.'} />
                        </StyledListItem>
                    </Spin>
                    <Spin size="small" spinning={loading && currentSettingToChange === Constants.settings.MENU_LAYOUT}>
                        <StyledListItem actions={
                            [
                                <Radio.Group
                                    size="small"
                                    options={
                                        [
                                            { label: 'Side', value: 'side' },
                                            { label: 'Top', value: 'top' },
                                        ]
                                    }
                                    onChange={menuLayoutChangeHandle}
                                    value={props.globalState.menuLayout}
                                    optionType="button"
                                    buttonStyle="solid"
                                />
                            ]
                        }>
                            <Item.Meta title={'Menu Layout'} description={'Change menu style.'} />
                        </StyledListItem>
                    </Spin>
                </List>
            </PageHeader>
            {
                colorPickerVisible && (
                    <ColorPickerPopup
                        selectedColor={props.globalState.accentColor}
                        visible={colorPickerVisible}
                        handleCancel={
                            () => {
                                setColorPickerVisible(false);
                            }
                        }
                        submitCallback={colorPickerSubmitCallback}
                        colorPickerOnChange={colorPickerOnChange}
                    />
                )
            }
        </React.Fragment>
    );
};

const mapStateToProps = state => {
    return {
        globalState: state.globalState
    };
};

const mapDispatchToProps = dispatch => ({
    setGlobalState: (state) => dispatch(setGlobalState(state)),
});

export default connect(mapStateToProps, mapDispatchToProps)(Themes);