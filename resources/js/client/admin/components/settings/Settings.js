import { Card } from 'antd';
import React from 'react';
import styled from 'styled-components';
import ZTabs from '../ZTabs';
import General from './General';
import Icon from '@ant-design/icons';
import { AiOutlineSetting } from 'react-icons/ai';
import { IoColorPaletteOutline } from 'react-icons/io5';
import Themes from './Themes';

const Wrapper = styled.div`
padding: 0;
background: #fff;
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
        content: <Themes/>
    }
]

const Settings = () => {
    return (
        <React.Fragment>
            <Wrapper className="z-shadow hoverable">
                <ZTabs tabs={tabs} selectedTab={'themes'}/>
            </Wrapper>
        </React.Fragment>
    )
}

export default Settings;