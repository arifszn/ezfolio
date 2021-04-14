import React from 'react';
import { Dropdown, Menu, Avatar } from 'antd';
import { LogoutOutlined, UserOutlined } from '@ant-design/icons';
import { useHistory } from 'react-router-dom';
import Routes from '../../../common/helpers/Routes';

const AvatarDropdown = () => {
    let history = useHistory();

    const onMenuClick = (event) => {
        const { key } = event;

        if (key === 'logout') {
            history.push(Routes.web.admin.logout);
        }
    }

    const menuHeaderDropdown = (
        <Menu selectedKeys={[]} onClick={onMenuClick}>
            <Menu.Item key="logout" style={{textAlign: 'center'}}>
                <LogoutOutlined />
                Log out
            </Menu.Item>
        </Menu>
    );

    return (
        <React.Fragment>
            <Dropdown overlay={menuHeaderDropdown}>
                <span>
                    <Avatar icon={<UserOutlined />} size="small" alt="avatar" />
                </span>
            </Dropdown>
        </React.Fragment>
    )
}

export default AvatarDropdown;