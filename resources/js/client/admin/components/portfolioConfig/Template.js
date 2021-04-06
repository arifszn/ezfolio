import React, { useEffect, useState } from 'react';
import { Card, Col, Row, Radio, Spin, Image, PageHeader, Typography } from 'antd';
import Utils from '../../../common/helpers/Utils';
import {BiLoader} from 'react-icons/bi';
import styled from 'styled-components';
import HTTP from '../../../common/helpers/HTTP';
import Routes from '../../../common/helpers/Routes';
import Constants from '../../../common/helpers/Constants';

const { Meta } = Card;
const { Text, Link } = Typography;

const selectedTemplateStyle = {
    padding: '.25rem',
    cursor: 'default',
    backgroundColor: 'var(--primary-shadow-color)'
}

const LazyLoadPlaceHolderWrapper = styled.div`
background: ghostwhite;
width: 100%;
height: 100%;
align-items: center;
justify-content: center;
-webkit-box-align: center;
display: inline-flex;
`;

const Template = (props) => {
    const [template, setTemplate] = useState(null);
    const [loading, setLoading] = useState(false);

    const onClickHandler = (selected) => {
        if (selected !== template) {
            if (!loading) {
                setLoading(true);
            }

            HTTP.post(Routes.api.admin.portfolioConfigs, {
                setting_key: Constants.config.TEMPLATE,
                setting_value: selected
            })
            .then(response => {
                Utils.handleSuccessResponse(response, () => {
                    setTemplate(selected);
                })
            })
            .catch(error => {
                Utils.handleException(error);
            })
            .finally(() => {
                setLoading(false);
            });
        }
    }

    useEffect(() => {
        if (props.config) {
            setTemplate(props.config);            
        }
    }, [props.config])

    return (
        <React.Fragment>
            <PageHeader
                title="Template Settings"
            >
                <Spin spinning={loading} delay={500} size="large">
                    <Radio.Group onChange={(e) => onClickHandler(e.target.value)} value={template}>
                        <Row gutter={24}>
                            {
                                Utils.templates.map((element, index) => {
                                    return (
                                        <Col key={index} xs={{ span: 24 }} lg={{ span: 8 }} style={{
                                            marginBottom: 24,
                                        }}>
                                            <Card
                                                bodyStyle={{padding: 8}}
                                                style={element.id === template ? selectedTemplateStyle : {}}
                                                onClick={() => onClickHandler(element.id)}
                                                cover={
                                                    <Image
                                                        className="z-shadow"
                                                        alt={element.title}
                                                        width={'100%'}
                                                        preview={false}
                                                        src={element.image}
                                                        placeholder={
                                                            <LazyLoadPlaceHolderWrapper>
                                                                <Spin/>
                                                            </LazyLoadPlaceHolderWrapper>
                                                        }
                                                    />
                                                }
                                            >
                                                <Meta
                                                    title={<Radio value={element.id}>{element.title}</Radio>}
                                                />
                                            </Card>
                                        </Col>
                                    )
                                })
                            }
                        </Row>
                    </Radio.Group>
                </Spin>
            </PageHeader>
        </React.Fragment>
    );
};

export default Template;