import React, { useEffect, useState } from 'react';
import ReactDOM from 'react-dom';
import { FaFolder, FaFolderOpen } from 'react-icons/fa';
import { IconContext } from 'react-icons';
import { Card, Image, List, Radio, Row, Space, Tabs, Typography } from 'antd';
import styled from 'styled-components';
import '../../common/assets/css/projects.scss';
import ProjectPreview from '../components/ProjectPreview';
import { InfoCircleOutlined } from '@ant-design/icons';
import { FaRegHandPointer } from 'react-icons/fa';
import Routes from '../../common/helpers/Routes';
import HTTP from '../../common/helpers/HTTP';
import Utils from '../../common/helpers/Utils';


const thumbnailStyle = {
    height: '150px',
    transition: '0.3s ease',
    // filter: 'opacity(0.8)',
    objectFit: 'cover'
}

function App() {
    const [loading, setLoading] = useState(true);
    const [modalVisible, setModalVisible] = useState(true);
    const [categories, setCategories] = useState([]);
    const [data, setData] = useState([]);

    useEffect(() => {
        loadData();
    }, [])

    const loadData = () => {
        setLoading(true);

        HTTP.get(Routes.api.frontend.projects, {
            isPrivate: false
        })
        .then(response => {
            Utils.handleSuccessResponse(response, () => {
                setData(response.data.payload);

                if (response.data.payload.length) {
                    let newCategories = [...categories];
                    response.data.payload.forEach(row => {
                        JSON.parse(row.categories).map((category, index) => {
                            newCategories.push(category);
                        })
                    });
                    setCategories([...new Set(newCategories)]);
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
            <div className="container">
                <div className="row">
                    <div className="text-center col-md-12">
                        <Space direction="vertical" size={'large'}>
                            <Radio.Group defaultValue="a">
                                <Radio.Button value="a">Hangzhou</Radio.Button>
                                <Radio.Button value="b">Shanghai</Radio.Button>
                                <Radio.Button value="c">Beijing</Radio.Button>
                                <Radio.Button value="d">Chengdu</Radio.Button>
                            </Radio.Group>
                            <List
                                grid={{ gutter: 24, xl:4, lg: 4, md: 2, sm: 1, xs: 1 }}
                                dataSource={data}
                                loading={loading}
                                renderItem={item => (
                                <List.Item style={{marginBottom: '24px'}}>
                                    <Card
                                        loading={loading}
                                        style={{width: '100%'}}
                                        bodyStyle={{padding: '14px'}}
                                        hoverable
                                        className={'z-hover z-shadow'}
                                        data-aos="fade-up" data-aos-anchor-placement="top-bottom"
                                        bordered={false}
                                        cover={
                                            <div style={{opacity: '0.7'}}>
                                                <Image
                                                    src={Utils.backend + '/' + item.thumbnail}
                                                    style={thumbnailStyle}
                                                    preview={false}
                                                    placeholder={true}
                                                />
                                            </div>
                                        }
                                        actions={[
                                            <React.Fragment key="view">
                                                See Details
                                            </React.Fragment>
                                        ]}
                                    >
                                        <Card.Meta
                                            title={item.title}
                                        />
                                    </Card>
                                    {/* <ProjectPreview/> */}
                                </List.Item>
                                )}
                            />
                        </Space>
                    </div>
                </div>
            </div>
            {/* <Wrapper>
                <Col span={24}>
                    <Tabs defaultActiveKey="1" centered>
                        <TabPane tab="Tab 1" key="1">
                        Content of Tab Pane 1
                        </TabPane>
                        <TabPane tab="Tab 2" key="2">
                        Content of Tab Pane 2
                        </TabPane>
                        <TabPane tab="Tab 3" key="3">
                        Content of Tab Pane 3
                        </TabPane>
                    </Tabs>
                </Col>
            </Wrapper> */}
            {/* <Row gutter={24}>
                <Col span={24}>
                    <Tabs defaultActiveKey="1" centered>
                        <TabPane tab="Tab 1" key="1">
                        Content of Tab Pane 1
                        </TabPane>
                        <TabPane tab="Tab 2" key="2">
                        Content of Tab Pane 2
                        </TabPane>
                        <TabPane tab="Tab 3" key="3">
                        Content of Tab Pane 3
                        </TabPane>
                    </Tabs>
                </Col>
            </Row> */}
        </React.Fragment>
    );
}

if (document.getElementById('react-project-root')) {
   ReactDOM.render(
        <React.StrictMode>
            <App />
        </React.StrictMode>,
        document.getElementById('react-project-root')
    ); 
}