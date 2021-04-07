import React, { useEffect, useState } from 'react';
import { Spin, List, PageHeader, Switch } from 'antd';
import PropTypes from 'prop-types';
import HTTP from '../../../common/helpers/HTTP';
import Routes from '../../../common/helpers/Routes';
import Utils from '../../../common/helpers/Utils';
import styled from 'styled-components';
import Constants from '../../../common/helpers/Constants';
import { CloseOutlined, CheckOutlined } from '@ant-design/icons';

const { Item } = List;

const StyledListItem = styled(Item)`
padding: 16px 0px !important;
`;

const Menu = (props) => {
    const [currentSettingToChange, setCurrentSettingToChange] = useState(null);
    const [loading, setLoading] = useState(false);
    const [about, setAbout] = useState(false);
    const [skills, setSkills] = useState(false);
    const [education, setEducation] = useState(false);
    const [experiences, setExperiences] = useState(false);
    const [projects, setProjects] = useState(false);
    const [services, setServices] = useState(false);
    const [contact, setContact] = useState(false);
    const [footer, setFooter] = useState(false);

    useEffect(() => {
        if (props.config) {
            setAbout(parseInt(props.config.menu.about));
            setSkills(parseInt(props.config.menu.skills));
            setEducation(parseInt(props.config.menu.education));
            setExperiences(parseInt(props.config.menu.experiences));
            setProjects(parseInt(props.config.menu.projects));
            setServices(parseInt(props.config.menu.services));
            setContact(parseInt(props.config.menu.contact));
            setFooter(parseInt(props.config.menu.footer));
        }
    }, [props.config])

    const submitData = (name, value, callback = null) => {
        if (!loading) {
            setLoading(true);
        }

        setCurrentSettingToChange(name);

        HTTP.post(Routes.api.admin.portfolioConfigs, {
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

    return (
        <React.Fragment>
            <PageHeader
                title="Enable/Disable Menu"
            >
                <List
                    itemLayout="horizontal"
                    size="large"
                >
                    <Spin delay={500} size="small" spinning={loading && currentSettingToChange === Constants.portfolioConfig.MENU_ABOUT}>
                        <StyledListItem actions={[
                            <Switch
                                key={'about'}
                                loading={loading && currentSettingToChange === Constants.portfolioConfig.MENU_ABOUT}
                                checkedChildren={<CheckOutlined />}
                                unCheckedChildren={<CloseOutlined />}
                                checked={about}
                                onChange={(checked) => {
                                    const callback = () => {
                                        setAbout(checked);
                                    }
                                    submitData(Constants.portfolioConfig.MENU_ABOUT, checked, callback);
                                }}
                            />
                        ]}>
                            <Item.Meta title={'About'} description={'Display about section.'}/>
                        </StyledListItem>
                    </Spin>
                    <Spin delay={500} size="small" spinning={loading && currentSettingToChange === Constants.portfolioConfig.MENU_SKILL}>
                        <StyledListItem actions={[
                            <Switch
                                key={'skills'}
                                loading={loading && currentSettingToChange === Constants.portfolioConfig.MENU_SKILL}
                                checkedChildren={<CheckOutlined />}
                                unCheckedChildren={<CloseOutlined />}
                                checked={skills}
                                onChange={(checked) => {
                                    const callback = () => {
                                        setSkills(checked);
                                    }
                                    submitData(Constants.portfolioConfig.MENU_SKILL, checked, callback);
                                }}
                            />
                        ]}>
                            <Item.Meta title={'Skills'} description={'Display skills section.'}/>
                        </StyledListItem>
                    </Spin>
                    <Spin delay={500} size="small" spinning={loading && currentSettingToChange === Constants.portfolioConfig.MENU_EDUCATION}>
                        <StyledListItem actions={[
                            <Switch
                                key={'education'}
                                loading={loading && currentSettingToChange === Constants.portfolioConfig.MENU_EDUCATION}
                                checkedChildren={<CheckOutlined />}
                                unCheckedChildren={<CloseOutlined />}
                                checked={education}
                                onChange={(checked) => {
                                    const callback = () => {
                                        setEducation(checked);
                                    }
                                    submitData(Constants.portfolioConfig.MENU_EDUCATION, checked, callback);
                                }}
                            />
                        ]}>
                            <Item.Meta title={'Education'} description={'Display education section.'}/>
                        </StyledListItem>
                    </Spin>
                    <Spin delay={500} size="small" spinning={loading && currentSettingToChange === Constants.portfolioConfig.MENU_EXPERIENCE}>
                        <StyledListItem actions={[
                            <Switch
                                key={'experiences'}
                                loading={loading && currentSettingToChange === Constants.portfolioConfig.MENU_EXPERIENCE}
                                checkedChildren={<CheckOutlined />}
                                unCheckedChildren={<CloseOutlined />}
                                checked={experiences}
                                onChange={(checked) => {
                                    const callback = () => {
                                        setExperiences(checked);
                                    }
                                    submitData(Constants.portfolioConfig.MENU_EXPERIENCE, checked, callback);
                                }}
                            />
                        ]}>
                            <Item.Meta title={'Experiences'} description={'Display experiences section.'}/>
                        </StyledListItem>
                    </Spin>
                    <Spin delay={500} size="small" spinning={loading && currentSettingToChange === Constants.portfolioConfig.MENU_PROJECT}>
                        <StyledListItem actions={[
                            <Switch
                                key={'projects'}
                                loading={loading && currentSettingToChange === Constants.portfolioConfig.MENU_PROJECT}
                                checkedChildren={<CheckOutlined />}
                                unCheckedChildren={<CloseOutlined />}
                                checked={projects}
                                onChange={(checked) => {
                                    const callback = () => {
                                        setProjects(checked);
                                    }
                                    submitData(Constants.portfolioConfig.MENU_PROJECT, checked, callback);
                                }}
                            />
                        ]}>
                            <Item.Meta title={'Projects'} description={'Display projects section.'}/>
                        </StyledListItem>
                    </Spin>
                    <Spin delay={500} size="small" spinning={loading && currentSettingToChange === Constants.portfolioConfig.MENU_SERVICE}>
                        <StyledListItem actions={[
                            <Switch
                                key={'services'}
                                loading={loading && currentSettingToChange === Constants.portfolioConfig.MENU_SERVICE}
                                checkedChildren={<CheckOutlined />}
                                unCheckedChildren={<CloseOutlined />}
                                checked={services}
                                onChange={(checked) => {
                                    const callback = () => {
                                        setServices(checked);
                                    }
                                    submitData(Constants.portfolioConfig.MENU_SERVICE, checked, callback);
                                }}
                            />
                        ]}>
                            <Item.Meta title={'Services'} description={'Display services section.'}/>
                        </StyledListItem>
                    </Spin>
                    <Spin delay={500} size="small" spinning={loading && currentSettingToChange === Constants.portfolioConfig.MENU_CONTACT}>
                        <StyledListItem actions={[
                            <Switch
                                key={'contact'}
                                loading={loading && currentSettingToChange === Constants.portfolioConfig.MENU_CONTACT}
                                checkedChildren={<CheckOutlined />}
                                unCheckedChildren={<CloseOutlined />}
                                checked={contact}
                                onChange={(checked) => {
                                    const callback = () => {
                                        setContact(checked);
                                    }
                                    submitData(Constants.portfolioConfig.MENU_CONTACT, checked, callback);
                                }}
                            />
                        ]}>
                            <Item.Meta title={'Contact Form'} description={'Display contact form section.'}/>
                        </StyledListItem>
                    </Spin>
                    <Spin delay={500} size="small" spinning={loading && currentSettingToChange === Constants.portfolioConfig.MENU_FOOTER}>
                        <StyledListItem actions={[
                            <Switch
                                key={'footer'}
                                loading={loading && currentSettingToChange === Constants.portfolioConfig.MENU_FOOTER}
                                checkedChildren={<CheckOutlined />}
                                unCheckedChildren={<CloseOutlined />}
                                checked={footer}
                                onChange={(checked) => {
                                    const callback = () => {
                                        setFooter(checked);
                                    }
                                    submitData(Constants.portfolioConfig.MENU_FOOTER, checked, callback);
                                }}
                            />
                        ]}>
                            <Item.Meta title={'Footer'} description={'Display footer section.'}/>
                        </StyledListItem>
                    </Spin>
                </List>
            </PageHeader>
        </React.Fragment>
    );
};

Menu.propTypes = {
    config: PropTypes.object,
}

export default Menu;