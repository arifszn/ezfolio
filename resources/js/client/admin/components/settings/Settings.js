import { Card } from 'antd';
import React from 'react';
import styled from 'styled-components';
import ZTabs from '../ZTabs';
import General from './General';
import Icon from '@ant-design/icons';
import { AiOutlineSetting } from 'react-icons/ai';
import { IoColorPaletteOutline } from 'react-icons/io5';

const StyledCard = styled(Card)`
.ant-card-body {
    padding: 0;
}
`;

const tabs = [
    {
        key: 'general-settings',
        title: <React.Fragment><Icon component={AiOutlineSetting}/> General Settings</React.Fragment>,
        content: <General/>
    },
    {
        key: 'themes',
        title: <React.Fragment><Icon component={IoColorPaletteOutline}/> Theme Settings</React.Fragment>,
        content: 'This is themes tab'
    }
]

const Settings = () => {
    return (
        <React.Fragment>
            <StyledCard className="z-shadow" hoverable={true} style={{padding: 0}} bordered={false}>
                <ZTabs tabs={tabs}/>
            </StyledCard>
        </React.Fragment>
    )
}

export default Settings;