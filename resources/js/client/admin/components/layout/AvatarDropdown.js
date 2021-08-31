import React from 'react';
import { Dropdown, Menu, Avatar } from 'antd';
import { LogoutOutlined, UserOutlined, ProfileOutlined, UnlockOutlined } from '@ant-design/icons';
import { useHistory } from 'react-router-dom';
import Routes from '../../../common/helpers/Routes';

const AvatarDropdown = () => {
    let history = useHistory();

    const onMenuClick = (event) => {
        const { key } = event;

        if (key === 'logout') {
            history.push(Routes.web.admin.logout);
        } else if (key === 'profile') {
            history.push(Routes.web.admin.portfolioAbout);
        } else if (key === 'password') {
            history.push(Routes.web.admin.settings);
        }
    }

    const menuHeaderDropdown = (
        <Menu selectedKeys={[]} onClick={onMenuClick}>
            <Menu.Item key="profile">
                <ProfileOutlined style={{marginRight: 10}}/>
                Profile
            </Menu.Item>
            <Menu.Item key="password">
                <UnlockOutlined style={{marginRight: 10}}/>
                Password
            </Menu.Item>
            <Menu.Item key="logout">
                <LogoutOutlined style={{marginRight: 10}}/>
                Log out
            </Menu.Item>
        </Menu>
    );

    return (
        <React.Fragment>
            <Dropdown overlay={menuHeaderDropdown} trigger={['click']}>
                <span>
                    <Avatar /* style={{ backgroundColor: 'var(--primary-hover-color)' }} */ icon={<UserOutlined />} size="small" alt="avatar" />
                </span>
            </Dropdown>
        </React.Fragment>
    )
}

export default AvatarDropdown;