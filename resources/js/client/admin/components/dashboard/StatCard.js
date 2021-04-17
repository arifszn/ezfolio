import React from 'react'
import { Card, Typography } from 'antd';
import { useHistory } from 'react-router-dom';
import styled from 'styled-components';
import CountUp from 'react-countup';
import PropTypes from 'prop-types';

const { Text } = Typography;

const Wrapper = styled.div`
cursor: pointer;

.icon-wrapper {
    font-size: 40px;
    float: left;
}

.stat-content {
    width: 100%;
    padding-left: 60px;

    .stat-title {
        line-height: 16px;
        font-size: 16px;
        margin-bottom: 8px;
        height: 16px;
        white-space: nowrap;
    }

    .stat-number {
        line-height: 32px;
        font-size: 24px;
        height: 32px;
        margin-bottom: 0;
        white-space: nowrap;
    }
}
`;

const StatCard = ({ icon, color, title, number, loading= false, link = false, isCard = true }) => {
    let history = useHistory();

    const children = (
        <Wrapper>
            <span className='icon-wrapper' style={{ color }}>
                {icon}
            </span>
            <div className='stat-content'>
                <p className='stat-title'>
                    <Text
                        style={{ width: '100%', color: 'grey' }}
                        ellipsis={{ tooltip: title || '' }}
                    >
                        {title || ''}
                    </Text>
                </p>

                <p className='stat-number'>
                    <CountUp
                        start={0}
                        end={number}
                        duration={4}
                        useEasing
                        useGrouping
                    />
                </p>
            </div>
        </Wrapper>
    );

    return (
        <React.Fragment>
        {
            isCard ? (
                <Card
                    onClick={() => {
                        if (link) {
                            history.push(link);
                        }
                    }}
                    loading={loading}
                    hoverable={true}
                    size="default"
                    bordered={false}
                    className="z-shadow"
                    style={!loading ? {padding: '18px 0'} : {}}
                >
                    {children}
                </Card>
            ) : (
                children
            )
        }
        </React.Fragment>
    )
}

StatCard.propTypes = {
    isCard: PropTypes.bool,
    link: PropTypes.string,
    loading: PropTypes.bool,
    number: PropTypes.number,
    title: PropTypes.string,
    color: PropTypes.string,
    icon: PropTypes.object.isRequired,
}

export default StatCard;
