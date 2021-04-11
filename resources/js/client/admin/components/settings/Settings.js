import React from 'react';
import ZTabs from '../ZTabs';
import General from './General';
import Icon from '@ant-design/icons';
import { AiOutlineSetting } from 'react-icons/ai';
import { IoColorPaletteOutline } from 'react-icons/io5';
import Themes from './Themes';
import Mail from './Mail';
import { RiMailSettingsLine } from 'react-icons/ri';
import PageWrapper from '../layout/PageWrapper';

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
    },
    {
        key: 'mail',
        title: <React.Fragment><Icon component={RiMailSettingsLine}/> Mail Settings</React.Fragment>,
        content: <Mail/>
    }
]

const Settings = () => {
    return (
        <React.Fragment>
            <PageWrapper noPadding>
                <ZTabs tabs={tabs}/>
            </PageWrapper>
        </React.Fragment>
    )
}

export default Settings;