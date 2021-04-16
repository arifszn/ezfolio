import React, { useEffect, useState } from 'react';
import moment from 'moment';
import HTTP from '../../../common/helpers/HTTP';
import Routes from '../../../common/helpers/Routes';
import Utils from '../../../common/helpers/Utils';
import { Button, Card, Col, Image, List, Row, Space, Spin, Statistic, Typography } from 'antd';
import Icon, { MessageOutlined, TeamOutlined, ArrowRightOutlined, ProfileOutlined, 
    ScheduleOutlined, 
    CarryOutOutlined, 
    InfoCircleOutlined, 
    CalendarOutlined, 
    RedoOutlined } from '@ant-design/icons';
import { BiLoader, BiArchive, BiMessageDots } from 'react-icons/bi';
import { Link } from 'react-router-dom';
import StatCard from './StatCard';
import { GoKeyboard } from 'react-icons/go';
import { HiOutlineBriefcase } from 'react-icons/hi';
import { GiSecretBook } from 'react-icons/gi';
import { RiServiceLine } from 'react-icons/ri';
import { TinyLine } from '@ant-design/charts';
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
    const [wallpaper, setWallpaper] = useState(null);
    const { demoMode } = useSelector(state => state.globalState);
    const [date, setDate] = useState(new Date());

    const todayStartDateUtc = moment.utc(moment().startOf('day')).format('YYYY-MM-DD HH:mm:ss');
    const todayEndDateUtc = moment.utc(moment().endOf('day')).format('YYYY-MM-DD HH:mm:ss');

    const thisWeekStartDateUtc = moment.utc(moment().startOf('week').startOf('day')).format('YYYY-MM-DD HH:mm:ss');
    const thisWeekEndDateUtc = moment.utc(moment().endOf('week').endOf('day')).format('YYYY-MM-DD HH:mm:ss');

    const thisMonthStartDateUtc = moment.utc(moment().startOf('month').startOf('day')).format('YYYY-MM-DD HH:mm:ss');
    const thisMonthEndDateUtc = moment.utc(moment().endOf('month').endOf('day')).format('YYYY-MM-DD HH:mm:ss');

    const visitorColor = Utils.randomHexColor();
    const messageColor = Utils.randomHexColor();

    const [loading, setLoading] = useState(true);

    const [visitorData, setVisitorData] = useState({
        total: 0,
        today: 0,
        thisWeek: 0,
        thisMonth: 0,
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

    useEffect(() => {
        loadData();
        getWallpaper();
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
                    setVisitorData({
                        total: result.visitors.total,
                        today: result.visitors.totalToday,
                        thisWeek: result.visitors.totalThisWeek,
                        thisMonth: result.visitors.totalThisMonth
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
                    xl={18}
                    lg={18}
                    md={24}
                    sm={24}
                    xs={24}
                >
                    <Row>
                        <Col 
                            xl={24}
                            lg={24}
                            md={24}
                            sm={24}
                            xs={24}
                            style={{
                                marginBottom: 24,
                            }}
                        >
                            <Row gutter={24}>
                                <Col 
                                    xl={6}
                                    lg={8}
                                    md={12}
                                    sm={24}
                                    xs={24}
                                    style={{marginBottom: 24}}
                                >
                                    <StatCard 
                                        link={Routes.web.admin.portfolioSkills} 
                                        loading={loading}
                                        icon={<Icon component={GoKeyboard}/>}
                                        color={Utils.randomHexColor()} 
                                        title='Skill' 
                                        number={skillData.total}
                                    />
                                </Col>
                                <Col 
                                    xl={6}
                                    lg={8}
                                    md={12}
                                    sm={24}
                                    xs={24}
                                    style={{marginBottom: 24}}
                                >
                                    <StatCard 
                                        link={Routes.web.admin.portfolioEducation} 
                                        loading={loading}
                                        icon={<Icon component={GiSecretBook}/>}
                                        color={Utils.randomHexColor()} 
                                        title='Education' 
                                        number={educationData.total}
                                    />
                                </Col>
                                <Col 
                                    xl={6}
                                    lg={8}
                                    md={12}
                                    sm={24}
                                    xs={24}
                                    style={{marginBottom: 24}}
                                >
                                    <StatCard 
                                        link={Routes.web.admin.portfolioExperiences} 
                                        loading={loading}
                                        icon={<Icon component={HiOutlineBriefcase}/>}
                                        color={Utils.randomHexColor()} 
                                        title='Experience' 
                                        number={experienceData.total}
                                    />
                                </Col>
                                <Col 
                                    xl={6}
                                    lg={8}
                                    md={12}
                                    sm={24}
                                    xs={24}
                                    style={{marginBottom: 24}}
                                >
                                    <StatCard 
                                        link={Routes.web.admin.portfolioProjects} 
                                        loading={loading}
                                        icon={<Icon component={BiArchive}/>}
                                        color={Utils.randomHexColor()} 
                                        title='Project' 
                                        number={projectData.total}
                                    />
                                </Col>
                                <Col 
                                    xl={6}
                                    lg={8}
                                    md={12}
                                    sm={24}
                                    xs={24}
                                    style={{marginBottom: 24}}
                                >
                                    <StatCard 
                                        link={Routes.web.admin.portfolioServices} 
                                        loading={loading}
                                        icon={<Icon component={RiServiceLine}/>}
                                        color={Utils.randomHexColor()} 
                                        title='Service' 
                                        number={servicesData.total}
                                    />
                                </Col>
                                <Col 
                                    xl={18}
                                    lg={8}
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
                                        bodyStyle={{textAlign: 'center'}}
                                    >
                                        <TinyLine
                                            height={75}
                                            showTitle={true}
                                            autoFit={true}
                                            data={[
    264,
    417,
    438,
    887,
    309,
    397,
    550,
    575,
    563,
    430,
    525,
    592,
    492,
    467,
    513,
    546,
    983,
    340,
    539,
    243,
    226,
    192,
  ]}
                                            smooth={true}
                                        />
                                        <Text type={'secondary'}>Visitor Trend</Text>
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
                                        bordered={false}
                                        loading={loading}
                                        className='z-shadow'
                                        bodyStyle={{textAlign: 'center'}}
                                        style={!loading ? {padding: '18px 0'} : {}}
                                    >
                                        <Row>
                                            <Col md={6} sm={12} xs={24}>
                                                <Statistic
                                                    title={'Total Visitors'}
                                                    value={visitorData.total}
                                                    valueStyle={{ color: visitorColor }}
                                                    prefix={<TeamOutlined/>}
                                                />
                                            </Col>
                                            <Col md={6} sm={12} xs={24}>
                                                <Statistic
                                                    title={'Visitors Today'}
                                                    value={visitorData.today}
                                                    valueStyle={{ color: visitorColor }}
                                                    prefix={<TeamOutlined/>}
                                                />
                                            </Col>
                                            <Col md={6} sm={12} xs={24}>
                                                <Statistic
                                                    title={'Visitors This Week'}
                                                    value={visitorData.thisWeek}
                                                    valueStyle={{ color: visitorColor }}
                                                    prefix={<TeamOutlined/>}
                                                />
                                            </Col>
                                            <Col md={6} sm={12} xs={24}>
                                                <Statistic
                                                    title={'Visitors This Month'}
                                                    value={visitorData.thisMonth}
                                                    valueStyle={{ color: visitorColor }}
                                                    prefix={<TeamOutlined/>}
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
                                        bordered={false}
                                        loading={loading}
                                        className='z-shadow'
                                        bodyStyle={{textAlign: 'center'}}
                                        style={!loading ? {padding: '18px 0'} : {}}
                                    >
                                        <Row>
                                            <Col md={6} sm={12} xs={24}>
                                                <Statistic
                                                    title={'Total Messages'}
                                                    value={messageData.total}
                                                    valueStyle={{ color: messageColor }}
                                                    prefix={<BiMessageDots/>}
                                                />
                                            </Col>
                                            <Col md={6} sm={12} xs={24}>
                                                <Statistic
                                                    title={'Messages Today'}
                                                    value={messageData.today}
                                                    valueStyle={{ color: messageColor }}
                                                    prefix={<BiMessageDots/>}
                                                />
                                            </Col>
                                            <Col md={6} sm={12} xs={24}>
                                                <Statistic
                                                    title={'Messages This Week'}
                                                    value={messageData.thisWeek}
                                                    valueStyle={{ color: messageColor }}
                                                    prefix={<BiMessageDots/>}
                                                />
                                            </Col>
                                            <Col md={6} sm={12} xs={24}>
                                                <Statistic
                                                    title={'Messages This Month'}
                                                    value={messageData.thisMonth}
                                                    valueStyle={{ color: messageColor }}
                                                    prefix={<BiMessageDots/>}
                                                />
                                            </Col>
                                        </Row>
                                    </Card>
                                </Col>
                            </Row>
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
                            style={{
                                marginBottom: 24,
                            }}
                        >
                            <Card
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
                                        style={imageStyle}
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
                            style={{
                                marginBottom: 24,
                            }}
                        >
                            <Card
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
                                        <div onClick={getWallpaper}><small>Get Another</small></div>
                                    }
                                />
                            </Card>
                        </Col>
                        <Col
                            xl={24}
                            lg={24}
                            md={24}
                            sm={24}
                            xs={24}
                            style={{
                                marginBottom: 24,
                            }}
                        >
                            <Card
                                hoverable={true}
                                bordered={false}
                                className='z-shadow'
                                loading={loading}
                                style={!loading ? {padding: '18px 0'} : {}}
                            >
                                <Row>
                                    <Col md={24} sm={24} xs={24}>
                                        <Statistic
                                                    title={date.toDateString()}
                                                    value={date.toLocaleTimeString()}
                                                    // prefix={date.toLocaleTimeString()}
                                                />
                                    </Col>
                                </Row>
                                {/* <Space direction="vertical">
                                    <Text>{date.toDateString()}</Text>
                                    <Text>{date.toLocaleTimeString()}</Text>
                                </Space> */}
                            </Card>
                        </Col>
                    </Row>
                </Col>
            </Row>
        </React.Fragment>
    )
}

export default Dashboard;