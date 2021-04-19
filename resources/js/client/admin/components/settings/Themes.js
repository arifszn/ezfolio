import { PageHeader, Radio, List, Spin } from 'antd';
import React, { useState } from 'react';
import { connect } from 'react-redux';
import { setGlobalState } from '../../redux/ActionCreators';
import styled from 'styled-components';
import ColorPickerPopup from '../ColorPickerPopup';
import Utils from '../../../common/helpers/Utils';
import HTTP from '../../../common/helpers/HTTP';
import Routes from '../../../common/helpers/Routes';
import CoreConstants from '../../../common/helpers/CoreConstants';
import PropTypes from 'prop-types';
import { useIsMobile } from '../../../common/hooks/IsMobile';

const { Item } = List;

const StyledListItem = styled(Item)`
padding: 16px 0px !important;
`;

const Themes = (props) => {
    const isMobile = useIsMobile();
    const [loading, setLoading] = useState(false);
    const [colorPickerVisible, setColorPickerVisible] = useState(false);
    const [currentSettingToChange, setCurrentSettingToChange] = useState(null);

    const colorPickerSubmitCallback = (color) => {
        submitData(CoreConstants.settings.ACCENT_COLOR, color);
    }

    const colorPickerCancelCallback = (color = null) => {
        if (color) {
            props.setGlobalState({
                accentColor: color
            });
            document.documentElement.style.setProperty('--z-accent-color', color);
            Utils.changeAccentColor(color);
        }
        setColorPickerVisible(false);
    }

    const colorPickerOnChange = (colorObject) => {
        const color = colorObject.hex;

        props.setGlobalState({
            accentColor: color
        });
        document.documentElement.style.setProperty('--z-accent-color', color);
        Utils.changeAccentColor(color);
    }

    const submitData = (name, value, callback = null) => {
        if (!loading) {
            setLoading(true);
        }

        setCurrentSettingToChange(name);

        HTTP.post(Routes.api.admin.settings, {
            setting_key: name,
            setting_value: value
        })
        .then(response => {
            Utils.handleSuccessResponse(response, () => {
                if (callback) {
                    callback();
                }
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

        const callback = () => {
            props.setGlobalState({
                menuLayout: value
            });
        }
        submitData(CoreConstants.settings.MENU_LAYOUT, value, callback);
    }

    const shortMenuChangeHandle = (e) => {
        const value = e.target.value;

        const callback = () => {
            props.setGlobalState({
                shortMenu: value
            });
        }
        submitData(CoreConstants.settings.SHORT_MENU, value, callback);
    }

    const menuColorChangeHandle = (e) => {
        const value = e.target.value;

        const callback = () => {
            props.setGlobalState({
                menuColor: value
            });
        }
        submitData(CoreConstants.settings.MENU_COLOR, value, callback);
    }

    const navColorChangeHandle = (e) => {
        const value = e.target.value;

        const callback = () => {
            props.setGlobalState({
                navColor: value
            });
        }
        submitData(CoreConstants.settings.NAV_COLOR, value, callback);
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
                    <Spin delay={500} size="small" spinning={loading && currentSettingToChange === CoreConstants.settings.ACCENT_COLOR}>
                        <StyledListItem actions={
                            [
                                <a 
                                    key="accent-color-change" 
                                    onClick={() => {
                                        setColorPickerVisible(true);
                                    }}
                                >
                                    Change
                                </a>,
                            ]
                        }>
                            <Item.Meta title={'Accent Color'} description={'Change accent color of admin portion.'} />
                        </StyledListItem>
                    </Spin>
                    <Spin delay={500} size="small" spinning={loading && currentSettingToChange === CoreConstants.settings.MENU_LAYOUT}>
                        <StyledListItem actions={
                            [
                                <Radio.Group
                                    key="change-menu-layout"
                                    size="small"
                                    options={
                                        [
                                            { label: 'Side', value: 'mix' },
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
                            <Item.Meta title={'Menu Layout'} description={'Change menu style for desktop screen.'} />
                        </StyledListItem>
                    </Spin>
                    {
                        props.globalState.menuLayout === 'mix' && (
                            <Spin delay={500} size="small" spinning={loading && currentSettingToChange === CoreConstants.settings.SHORT_MENU}>
                                <StyledListItem actions={
                                    [
                                        <Radio.Group
                                            key="change-short-menu"
                                            size="small"
                                            options={
                                                [
                                                    { label: 'Short', value: true },
                                                    { label: 'Full', value: false },
                                                ]
                                            }
                                            onChange={shortMenuChangeHandle}
                                            value={props.globalState.shortMenu}
                                            optionType="button"
                                            buttonStyle="solid"
                                        />
                                    ]
                                }>
                                    <Item.Meta title={'Menu Size'} description={'Change default menu size.'} />
                                </StyledListItem>
                            </Spin>
                        )
                    }
                    <Spin delay={500} size="small" spinning={loading && currentSettingToChange === CoreConstants.settings.MENU_COLOR}>
                        <StyledListItem actions={
                            [
                                <Radio.Group
                                    key="change-menu-color"
                                    size="small"
                                    options={
                                        [
                                            { label: 'Dark', value: 'dark' },
                                            { label: 'Light', value: 'light' },
                                        ]
                                    }
                                    onChange={menuColorChangeHandle}
                                    value={props.globalState.menuColor}
                                    optionType="button"
                                    buttonStyle="solid"
                                />
                            ]
                        }>
                            <Item.Meta title={'Menu Layout'} description={'Change menu color.'} />
                        </StyledListItem>
                    </Spin>
                    {
                       ( isMobile || (props.globalState.menuLayout === 'mix')) && (
                            <Spin delay={500} size="small" spinning={loading && currentSettingToChange === CoreConstants.settings.NAV_COLOR}>
                                <StyledListItem actions={
                                    [
                                        <Radio.Group
                                            key="change-nav-color"
                                            size="small"
                                            options={
                                                [
                                                    { label: 'Dark', value: 'dark' },
                                                    { label: 'Light', value: 'light' },
                                                ]
                                            }
                                            onChange={navColorChangeHandle}
                                            value={props.globalState.navColor}
                                            optionType="button"
                                            buttonStyle="solid"
                                        />
                                    ]
                                }>
                                    <Item.Meta title={'Nav Bar Color'} description={'Change nav color.'} />
                                </StyledListItem>
                            </Spin>
                        )
                    }
                </List>
            </PageHeader>
            {
                colorPickerVisible && (
                    <ColorPickerPopup
                        selectedColor={props.globalState.accentColor}
                        visible={colorPickerVisible}
                        handleCancel={colorPickerCancelCallback}
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

Themes.propTypes = {
    globalState: PropTypes.object,
    setGlobalState: PropTypes.func,
}

export default connect(mapStateToProps, mapDispatchToProps)(Themes);