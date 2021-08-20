import React, { useEffect, useState } from 'react';
import moment from 'moment';
import HTTP from '../../../common/helpers/HTTP';
import Routes from '../../../common/helpers/Routes';
import Utils from '../../../common/helpers/Utils';
import { Card, Col, Image, notification, Row, Spin, Typography, Space } from 'antd';
import Icon, { InfoCircleOutlined } from '@ant-design/icons';
import { BiArchive } from 'react-icons/bi';
import { useHistory } from 'react-router-dom';
import StatCard from './StatCard';
import { GoKeyboard } from 'react-icons/go';
import { BsBriefcase } from 'react-icons/bs';
import { GiSecretBook } from 'react-icons/gi';
import { AiOutlineTeam } from 'react-icons/ai';
import { RiServiceLine, RiMessage3Line } from 'react-icons/ri';
import { TinyArea } from '@ant-design/charts';
import RedditImageFetcher from 'reddit-image-fetcher';
import styled from 'styled-components';
import { useSelector } from 'react-redux';

const { Text } = Typography;

const imageHeight = 155;

const imageStyle = {
    objectFit: 'cover',
    opacity: '0.8'
}

const WallpaperLoading = styled.div`
    background: ghostwhite;
    width: 100%;
    height: ${imageHeight}px;
    align-items: center;
    justify-content: center;
    -webkit-box-align: center;
    display: inline-flex;
`;

const Dashboard = () => {
    let history = useHistory();

    const [wallpaper, setWallpaper] = useState(null);
    const { demoMode } = useSelector(state => state.globalState);

    const todayStartDateUtc = moment.utc(moment().startOf('day')).format('YYYY-MM-DD HH:mm:ss');
    const todayEndDateUtc = moment.utc(moment().endOf('day')).format('YYYY-MM-DD HH:mm:ss');

    const thisWeekStartDateUtc = moment.utc(moment().startOf('week').startOf('day')).format('YYYY-MM-DD HH:mm:ss');
    const thisWeekEndDateUtc = moment.utc(moment().endOf('week').endOf('day')).format('YYYY-MM-DD HH:mm:ss');

    const thisMonthStartDateUtc = moment.utc(moment().startOf('month').startOf('day')).format('YYYY-MM-DD HH:mm:ss');
    const thisMonthEndDateUtc = moment.utc(moment().endOf('month').endOf('day')).format('YYYY-MM-DD HH:mm:ss');

    const [loading, setLoading] = useState(true);

    const [visitorData, setVisitorData] = useState({
        total: 0,
        today: 0,
        thisWeek: 0,
        thisMonth: 0,
        trend: []
    });
    const [messageData, setMessageData] = useState({
        total: 0,
        today: 0,
        thisWeek: 0,
        thisMonth: 0,
    });

    const [skillData, setSkillData] = useState({
        total: 0
    });

    const [educationData, setEducationData] = useState({
        total: 0
    });

    const [experienceData, setExperienceData] = useState({
        total: 0
    });

    const [projectData, setProjectData] = useState({
        total: 0
    });

    const [servicesData, setServicesData] = useState({
        total: 0
    });

    const [currentTemplate, setCurrentTemplate] = useState(null);

    const [colors, setColors] = useState({});

    useEffect(() => {
        setColors({
            skill: Utils.randomHexColor(),
            education: Utils.randomHexColor(),
            experience: Utils.randomHexColor(),
            project: Utils.randomHexColor(),
            service: Utils.randomHexColor(),
            visitor: Utils.randomHexColor(),
            message: Utils.randomHexColor(),
        })

        loadData();
        getWallpaper();

        if (demoMode) {
            notification.open({
                message: (
                    <div className="text-center">
                        <a target="_blank" rel="noreferrer" href="https://github.com/arifszn/ezfolio">
                            <img src="https://img.shields.io/github/stars/arifszn/ezfolio?style=social" alt="Github Star"/>
                        </a>
                    </div>
                ),
                description: <React.Fragment>
                    <Space direction="vertical" size="middle">
                        <div className="text-center">
                            Show your ❤️ and support by giving a ⭐️ on <a target="_blank" rel="noreferrer" href="https://github.com/arifszn/ezfolio">GitHub</a>.
                        </div>
                        <div className="text-center">
                            <a href={Routes.web.frontend.home} target="_blank" rel="noreferrer">Visit Front Panel</a>
                        </div>
                    </Space>
                </React.Fragment>,
                placement: 'bottomRight',
                duration: 0,
                key: 'star-notification'
            });
        }
    }, []);

    const getWallpaper = () => {
        setWallpaper(null);

        RedditImageFetcher
        .fetch({type: 'wallpaper'})
        .then(result => {
            if (result.length) {
                setWallpaper(result[0]);
            }
        });
    }

    const loadData = (_loading = true) => {
        setLoading(_loading);

        HTTP.get(Routes.api.admin.stats, {   
            params: {
                todayStartDate: todayStartDateUtc,
                todayEndDate: todayEndDateUtc,
                thisWeekStartDate: thisWeekStartDateUtc,
                thisWeekEndDate: thisWeekEndDateUtc,
                thisMonthStartDate: thisMonthStartDateUtc,
                thisMonthEndDate: thisMonthEndDateUtc,
            }
        })
        .then(response => {
            Utils.handleSuccessResponse(response, () => {
                const result = response.data.payload;

                if (result) {
                    //visitors
                    let trendArray = [];

                    result.visitors.trend.forEach(element => {
                        trendArray.push(parseInt(element.count));
                    });

                    if (trendArray.length === 0) {
                        trendArray = [0, 0];
                    } else if (trendArray.length === 1) {
                        trendArray.unshift(0);
                    }

                    setVisitorData({
                        total: result.visitors.total,
                        today: result.visitors.totalToday,
                        thisWeek: result.visitors.totalThisWeek,
                        thisMonth: result.visitors.totalThisMonth,
                        trend: trendArray
                    });

                    //message
                    setMessageData({
                        total: result.message.total,
                        today: result.message.totalToday,
                        thisWeek: result.message.totalThisWeek,
                        thisMonth: result.message.totalThisMonth
                    });

                    //skills
                    setSkillData({
                        total: result.skills.total,
                    });

                    //education
                    setEducationData({
                        total: result.educations.total,
                    });

                    //experience
                    setExperienceData({
                        total: result.experiences.total,
                    });

                    //project
                    setProjectData({
                        total: result.projects.total,
                    });

                    //service
                    setServicesData({
                        total: result.services.total,
                    });

                    //template
                    const filteredArray = Utils.templates.filter(template => template.id === result.currentTemplate);
                    if (filteredArray.length) {
                        setCurrentTemplate(filteredArray[0]);
                    }
                }
            });
        })
        .catch(error => {
            Utils.handleException(error);
        }).finally(() => {
            setLoading(false);
        });
    }

    return (
        <React.Fragment>
            <Row gutter={24}>
                <Col 
                    xl={24}
                    lg={24}
                    md={24}
                    sm={24}
                    xs={24}
                >
                    <Row gutter={24}>
                        <Col 
                            xl={6}
                            lg={6}
                            md={12}
                            sm={24}
                            xs={24}
                            style={{marginBottom: 24}}
                        >
                            <StatCard 
                                link={Routes.web.admin.portfolioSkills} 
                                loading={loading}
                                icon={<Icon component={GoKeyboard}/>}
                                color={colors.skill} 
                                title='Skill' 
                                number={skillData.total}
                            />
                        </Col>
                        <Col 
                            xl={6}
                            lg={6}
                            md={12}
                            sm={24}
                            xs={24}
                            style={{marginBottom: 24}}
                        >
                            <StatCard 
                                link={Routes.web.admin.portfolioEducation} 
                                loading={loading}
                                icon={<Icon component={GiSecretBook}/>}
                                color={colors.education} 
                                title='Education' 
                                number={educationData.total}
                            />
                        </Col>
                        <Col 
                            xl={6}
                            lg={6}
                            md={12}
                            sm={24}
                            xs={24}
                            style={{marginBottom: 24}}
                        >
                            <StatCard 
                                link={Routes.web.admin.portfolioExperiences} 
                                loading={loading}
                                icon={<Icon component={BsBriefcase}/>}
                                color={colors.experience} 
                                title='Experience' 
                                number={experienceData.total}
                            />
                        </Col>
                        <Col 
                            xl={6}
                            lg={6}
                            md={12}
                            sm={24}
                            xs={24}
                            style={{marginBottom: 24}}
                        >
                            <StatCard 
                                link={Routes.web.admin.portfolioProjects} 
                                loading={loading}
                                icon={<Icon component={BiArchive}/>}
                                color={colors.project} 
                                title='Project' 
                                number={projectData.total}
                            />
                        </Col>
                    </Row>
                </Col>
                <Col 
                    xl={18}
                    lg={18}
                    md={24}
                    sm={24}
                    xs={24}
                >
                    <Row gutter={24}>
                        <Col 
                            xl={8}
                            lg={10}
                            md={12}
                            sm={24}
                            xs={24}
                            style={{marginBottom: 24}}
                        >
                            <StatCard 
                                link={Routes.web.admin.portfolioServices} 
                                loading={loading}
                                icon={<Icon component={RiServiceLine}/>}
                                color={colors.service} 
                                title='Service' 
                                number={servicesData.total}
                            />
                        </Col>
                        <Col 
                            xl={16}
                            lg={14}
                            md={12}
                            sm={24}
                            xs={24}
                            style={{marginBottom: 24}}
                        >
                            <Card
                                hoverable={true}
                                bordered={false}
                                loading={loading}
                                className='z-shadow'
                                style={{cursor: 'default'}}
                            >
                                <Row>
                                    <Col md={24} sm={24} xs={24} style={{textAlign: 'center'}}>
                                        <Text type={'secondary'}>Visitor Trend</Text>
                                    </Col>
                                    <Col md={24} sm={24} xs={24} style={{textAlign: 'center'}}>
                                        <TinyArea
                                            height={75}
                                            showTitle={true}
                                            autoFit={true}
                                            data={visitorData.trend}
                                            smooth={true}
                                        />
                                    </Col>
                                </Row>
                            </Card>
                        </Col>
                        <Col
                            xl={24}
                            lg={24}
                            md={24}
                            sm={24}
                            xs={24}
                            style={{marginBottom: 24}}
                        >
                            <Card
                                hoverable={true}
                                onClick={() => {
                                    history.push(Routes.web.admin.visitors);
                                }}
                                bordered={false}
                                loading={loading}
                                className='z-shadow'
                            >
                                <Row>
                                    <Col md={24} sm={24} xs={24} style={{textAlign: 'center', paddingBottom: '14px'}}>
                                        <Text type={'secondary'}>Visitor</Text>
                                    </Col>
                                    <Col md={6} sm={24} xs={24}>
                                        <StatCard
                                            isCard={false}
                                            loading={loading}
                                            icon={<Icon component={AiOutlineTeam}/>}
                                            color={colors.visitor} 
                                            title='Total' 
                                            number={visitorData.total}
                                        />
                                    </Col>
                                    <Col md={6} sm={24} xs={24}>
                                        <StatCard
                                            isCard={false}
                                            loading={loading}
                                            icon={<Icon component={AiOutlineTeam}/>}
                                            color={colors.visitor} 
                                            title='This Month' 
                                            number={visitorData.thisMonth}
                                        />
                                    </Col>
                                    <Col md={6} sm={24} xs={24}>
                                        <StatCard
                                            isCard={false}
                                            loading={loading}
                                            icon={<Icon component={AiOutlineTeam}/>}
                                            color={colors.visitor} 
                                            title='This Week' 
                                            number={visitorData.thisWeek}
                                        />
                                    </Col>
                                    <Col md={6} sm={24} xs={24}>
                                        <StatCard
                                            isCard={false}
                                            loading={loading}
                                            icon={<Icon component={AiOutlineTeam}/>}
                                            color={colors.visitor} 
                                            title='Today' 
                                            number={visitorData.today}
                                        />
                                    </Col>
                                </Row>
                            </Card>
                        </Col>
                        <Col
                            xl={24}
                            lg={24}
                            md={24}
                            sm={24}
                            xs={24}
                            style={{marginBottom: 24}}
                        >
                        <Card
                                hoverable={true}
                                onClick={() => {
                                    history.push(Routes.web.admin.messages);
                                }}
                                bordered={false}
                                loading={loading}
                                className='z-shadow'
                            >
                                <Row>
                                    <Col md={24} sm={24} xs={24} style={{textAlign: 'center', paddingBottom: '14px'}}>
                                        <Text type={'secondary'}>Message</Text>
                                    </Col>
                                    <Col md={6} sm={24} xs={24}>
                                        <StatCard
                                            isCard={false}
                                            loading={loading}
                                            icon={<Icon component={RiMessage3Line}/>}
                                            color={colors.message} 
                                            title='Total' 
                                            number={messageData.total}
                                        />
                                    </Col>
                                    <Col md={6} sm={24} xs={24}>
                                        <StatCard
                                            isCard={false}
                                            loading={loading}
                                            icon={<Icon component={RiMessage3Line}/>}
                                            color={colors.message} 
                                            title='This Month' 
                                            number={messageData.thisMonth}
                                        />
                                    </Col>
                                    <Col md={6} sm={24} xs={24}>
                                        <StatCard
                                            isCard={false}
                                            loading={loading}
                                            icon={<Icon component={RiMessage3Line}/>}
                                            color={colors.message} 
                                            title='This Week' 
                                            number={messageData.thisWeek}
                                        />
                                    </Col>
                                    <Col md={6} sm={24} xs={24}>
                                        <StatCard
                                            isCard={false}
                                            loading={loading}
                                            icon={<Icon component={RiMessage3Line}/>}
                                            color={colors.message} 
                                            title='Today' 
                                            number={messageData.today}
                                        />
                                    </Col>
                                </Row>
                            </Card>
                        </Col>
                    </Row>
                </Col>
                <Col 
                    xl={6}
                    lg={6}
                    md={24}
                    sm={24}
                    xs={24}
                >
                    <Row gutter={24}>
                        <Col
                            xl={24}
                            lg={24}
                            md={12}
                            sm={24}
                            xs={24}
                            style={{marginBottom: 24}}
                        >
                            <Card
                                onClick={() => {
                                    history.push(Routes.web.admin.portfolioConfig);
                                }}
                                hoverable={true}
                                bordered={false}
                                size={'small'}
                                loading={loading}
                                className='z-shadow'
                                cover={
                                    <Image
                                        alt={currentTemplate && currentTemplate.title}
                                        width={'100%'}
                                        height={imageHeight}
                                        style={{
                                            objectFit: 'fill',
                                            opacity: '0.8'
                                        }}
                                        preview={false}
                                        src={currentTemplate && currentTemplate.image}
                                        placeholder={<Spin><WallpaperLoading/></Spin>}
                                    />
                                }
                            >
                                <Card.Meta
                                    title={<React.Fragment><small>{currentTemplate && currentTemplate.title}</small></React.Fragment>}
                                    description={<React.Fragment><small>Change Template</small></React.Fragment>}
                                />
                            </Card>
                        </Col>
                        <Col
                            xl={24}
                            lg={24}
                            md={12}
                            sm={24}
                            xs={24}
                        >
                            <Card
                                style={{cursor: 'default'}}
                                hoverable={true}
                                size="small"
                                bordered={false}
                                className='z-shadow'
                                loading={loading}
                                cover={
                                    wallpaper ? (
                                        <Image
                                            height={imageHeight}
                                            src={wallpaper.image}
                                            style={imageStyle}
                                            placeholder={
                                                <Image
                                                    height={imageHeight}
                                                    width={'100%'}
                                                    preview={false}
                                                    src={wallpaper.thumbnail}
                                                    style={imageStyle}
                                                />
                                            }
                                        />
                                    ) : (
                                        <Spin><WallpaperLoading/></Spin>
                                    )
                                    
                                }
                            >
                                <Card.Meta
                                    title={<React.Fragment><small>Daily Wallpaper {demoMode && (
                                        <a href="https://github.com/arifszn/reddit-image-fetcher" target="_blank" rel="noreferrer"><InfoCircleOutlined style={{paddingLeft: '2px', color: 'rgba(0, 0, 0, 0.45)'}}/></a>
                                    )}</small></React.Fragment>}
                                    description={
                                        <div onClick={getWallpaper} style={{cursor: 'pointer'}}><small>Get Another</small></div>
                                    }
                                />
                            </Card>
                        </Col>
                    </Row>
                </Col>
            </Row>
        </React.Fragment>
    )
}

export default React.memo(Dashboard);