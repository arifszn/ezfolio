import React from 'react';
import { Dropdown, Menu, Avatar } from 'antd';
import { LogoutOutlined, UserOutlined } from '@ant-design/icons';
import { useHistory } from 'react-router-dom';
import styled from 'styled-components';
import Routes from '../../../common/helpers/Routes';

const Wrapper = styled.div`
.ant-dropdown-trigger {
    align-items: center;
    height: 100%;
    padding: 0 12px;
    cursor: pointer;
    transition: all .3s;
    justify-content: center;
    -webkit-box-align: center;
    display: inline-flex;
    &:hover {
        background: rgba(0,0,0,.025);
    }
}
`;

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
            <Wrapper>
                <Dropdown overlay={menuHeaderDropdown}>
                    <span>
                        <Avatar icon={<UserOutlined />} size="small" alt="avatar" />
                    </span>
                </Dropdown>
            </Wrapper>
        </React.Fragment>
    )
}

export default AvatarDropdown;