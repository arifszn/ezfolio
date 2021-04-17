import React, { useEffect, useState } from 'react';
import { Spin, List, PageHeader, Switch } from 'antd';
import PropTypes from 'prop-types';
import HTTP from '../../../common/helpers/HTTP';
import Routes from '../../../common/helpers/Routes';
import Utils from '../../../common/helpers/Utils';
import styled from 'styled-components';
import CoreConstants from '../../../common/helpers/CoreConstants';
import { CloseOutlined, CheckOutlined } from '@ant-design/icons';

const { Item } = List;

const StyledListItem = styled(Item)`
padding: 16px 0px !important;
`;

const Visibility = (props) => {
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
    const [cv, setCv] = useState(false);
    const [skillProficiency, setSkillProficiency] = useState(false);

    useEffect(() => {
        props.mountedCallBack();
    }, [])

    useEffect(() => {
        if (props.config) {
            setAbout(parseInt(props.config.visibility.about));
            setSkills(parseInt(props.config.visibility.skills));
            setEducation(parseInt(props.config.visibility.education));
            setExperiences(parseInt(props.config.visibility.experiences));
            setProjects(parseInt(props.config.visibility.projects));
            setServices(parseInt(props.config.visibility.services));
            setContact(parseInt(props.config.visibility.contact));
            setFooter(parseInt(props.config.visibility.footer));
            setCv(parseInt(props.config.visibility.cv));
            setSkillProficiency(parseInt(props.config.visibility.skillProficiency));
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
                title="Visibility"
            >
                <List
                    itemLayout="horizontal"
                    size="large"
                >
                    <Spin delay={500} size="small" spinning={loading && currentSettingToChange === CoreConstants.portfolioConfig.VISIBILITY_ABOUT}>
                        <StyledListItem actions={[
                            <Switch
                                key={'about'}
                                loading={loading && currentSettingToChange === CoreConstants.portfolioConfig.VISIBILITY_ABOUT}
                                checkedChildren={<CheckOutlined />}
                                unCheckedChildren={<CloseOutlined />}
                                checked={about}
                                onChange={(checked) => {
                                    const callback = () => {
                                        setAbout(checked);
                                    }
                                    submitData(CoreConstants.portfolioConfig.VISIBILITY_ABOUT, checked, callback);
                                }}
                            />
                        ]}>
                            <Item.Meta title={'About'} description={'Display about section.'}/>
                        </StyledListItem>
                    </Spin>
                    <Spin delay={500} size="small" spinning={loading && currentSettingToChange === CoreConstants.portfolioConfig.VISIBILITY_SKILL}>
                        <StyledListItem actions={[
                            <Switch
                                key={'skills'}
                                loading={loading && currentSettingToChange === CoreConstants.portfolioConfig.VISIBILITY_SKILL}
                                checkedChildren={<CheckOutlined />}
                                unCheckedChildren={<CloseOutlined />}
                                checked={skills}
                                onChange={(checked) => {
                                    const callback = () => {
                                        setSkills(checked);
                                    }
                                    submitData(CoreConstants.portfolioConfig.VISIBILITY_SKILL, checked, callback);
                                }}
                            />
                        ]}>
                            <Item.Meta title={'Skills'} description={'Display skills section.'}/>
                        </StyledListItem>
                    </Spin>
                    <Spin delay={500} size="small" spinning={loading && currentSettingToChange === CoreConstants.portfolioConfig.VISIBILITY_EDUCATION}>
                        <StyledListItem actions={[
                            <Switch
                                key={'education'}
                                loading={loading && currentSettingToChange === CoreConstants.portfolioConfig.VISIBILITY_EDUCATION}
                                checkedChildren={<CheckOutlined />}
                                unCheckedChildren={<CloseOutlined />}
                                checked={education}
                                onChange={(checked) => {
                                    const callback = () => {
                                        setEducation(checked);
                                    }
                                    submitData(CoreConstants.portfolioConfig.VISIBILITY_EDUCATION, checked, callback);
                                }}
                            />
                        ]}>
                            <Item.Meta title={'Education'} description={'Display education section.'}/>
                        </StyledListItem>
                    </Spin>
                    <Spin delay={500} size="small" spinning={loading && currentSettingToChange === CoreConstants.portfolioConfig.VISIBILITY_EXPERIENCE}>
                        <StyledListItem actions={[
                            <Switch
                                key={'experiences'}
                                loading={loading && currentSettingToChange === CoreConstants.portfolioConfig.VISIBILITY_EXPERIENCE}
                                checkedChildren={<CheckOutlined />}
                                unCheckedChildren={<CloseOutlined />}
                                checked={experiences}
                                onChange={(checked) => {
                                    const callback = () => {
                                        setExperiences(checked);
                                    }
                                    submitData(CoreConstants.portfolioConfig.VISIBILITY_EXPERIENCE, checked, callback);
                                }}
                            />
                        ]}>
                            <Item.Meta title={'Experiences'} description={'Display experiences section.'}/>
                        </StyledListItem>
                    </Spin>
                    <Spin delay={500} size="small" spinning={loading && currentSettingToChange === CoreConstants.portfolioConfig.VISIBILITY_PROJECT}>
                        <StyledListItem actions={[
                            <Switch
                                key={'projects'}
                                loading={loading && currentSettingToChange === CoreConstants.portfolioConfig.VISIBILITY_PROJECT}
                                checkedChildren={<CheckOutlined />}
                                unCheckedChildren={<CloseOutlined />}
                                checked={projects}
                                onChange={(checked) => {
                                    const callback = () => {
                                        setProjects(checked);
                                    }
                                    submitData(CoreConstants.portfolioConfig.VISIBILITY_PROJECT, checked, callback);
                                }}
                            />
                        ]}>
                            <Item.Meta title={'Projects'} description={'Display projects section.'}/>
                        </StyledListItem>
                    </Spin>
                    <Spin delay={500} size="small" spinning={loading && currentSettingToChange === CoreConstants.portfolioConfig.VISIBILITY_SERVICE}>
                        <StyledListItem actions={[
                            <Switch
                                key={'services'}
                                loading={loading && currentSettingToChange === CoreConstants.portfolioConfig.VISIBILITY_SERVICE}
                                checkedChildren={<CheckOutlined />}
                                unCheckedChildren={<CloseOutlined />}
                                checked={services}
                                onChange={(checked) => {
                                    const callback = () => {
                                        setServices(checked);
                                    }
                                    submitData(CoreConstants.portfolioConfig.VISIBILITY_SERVICE, checked, callback);
                                }}
                            />
                        ]}>
                            <Item.Meta title={'Services'} description={'Display services section.'}/>
                        </StyledListItem>
                    </Spin>
                    <Spin delay={500} size="small" spinning={loading && currentSettingToChange === CoreConstants.portfolioConfig.VISIBILITY_CONTACT}>
                        <StyledListItem actions={[
                            <Switch
                                key={'contact'}
                                loading={loading && currentSettingToChange === CoreConstants.portfolioConfig.VISIBILITY_CONTACT}
                                checkedChildren={<CheckOutlined />}
                                unCheckedChildren={<CloseOutlined />}
                                checked={contact}
                                onChange={(checked) => {
                                    const callback = () => {
                                        setContact(checked);
                                    }
                                    submitData(CoreConstants.portfolioConfig.VISIBILITY_CONTACT, checked, callback);
                                }}
                            />
                        ]}>
                            <Item.Meta title={'Contact Form'} description={'Display contact form section.'}/>
                        </StyledListItem>
                    </Spin>
                    <Spin delay={500} size="small" spinning={loading && currentSettingToChange === CoreConstants.portfolioConfig.VISIBILITY_FOOTER}>
                        <StyledListItem actions={[
                            <Switch
                                key={'footer'}
                                loading={loading && currentSettingToChange === CoreConstants.portfolioConfig.VISIBILITY_FOOTER}
                                checkedChildren={<CheckOutlined />}
                                unCheckedChildren={<CloseOutlined />}
                                checked={footer}
                                onChange={(checked) => {
                                    const callback = () => {
                                        setFooter(checked);
                                    }
                                    submitData(CoreConstants.portfolioConfig.VISIBILITY_FOOTER, checked, callback);
                                }}
                            />
                        ]}>
                            <Item.Meta title={'Footer'} description={'Display footer section.'}/>
                        </StyledListItem>
                    </Spin>
                    <Spin delay={500} size="small" spinning={loading && currentSettingToChange === CoreConstants.portfolioConfig.VISIBILITY_CV}>
                        <StyledListItem actions={[
                            <Switch
                                key={'cv'}
                                loading={loading && currentSettingToChange === CoreConstants.portfolioConfig.VISIBILITY_CV}
                                checkedChildren={<CheckOutlined />}
                                unCheckedChildren={<CloseOutlined />}
                                checked={cv}
                                onChange={(checked) => {
                                    const callback = () => {
                                        setCv(checked);
                                    }
                                    submitData(CoreConstants.portfolioConfig.VISIBILITY_CV, checked, callback);
                                }}
                            />
                        ]}>
                            <Item.Meta title={'CV'} description={'Display CV download button.'}/>
                        </StyledListItem>
                    </Spin>
                    <Spin delay={500} size="small" spinning={loading && currentSettingToChange === CoreConstants.portfolioConfig.VISIBILITY_SKILL_PROFICIENCY}>
                        <StyledListItem actions={[
                            <Switch
                                key={'skill-proficiency'}
                                loading={loading && currentSettingToChange === CoreConstants.portfolioConfig.VISIBILITY_SKILL_PROFICIENCY}
                                checkedChildren={<CheckOutlined />}
                                unCheckedChildren={<CloseOutlined />}
                                checked={skillProficiency}
                                onChange={(checked) => {
                                    const callback = () => {
                                        setSkillProficiency(checked);
                                    }
                                    submitData(CoreConstants.portfolioConfig.VISIBILITY_SKILL_PROFICIENCY, checked, callback);
                                }}
                            />
                        ]}>
                            <Item.Meta title={'Skill Proficiency'} description={'Display proficiency bar of skills.'}/>
                        </StyledListItem>
                    </Spin>
                </List>
            </PageHeader>
        </React.Fragment>
    );
};

Visibility.propTypes = {
    config: PropTypes.object,
    mountedCallBack: PropTypes.func.isRequired
}

export default Visibility;