import { Button, Menu, PageHeader, Space, Dropdown, Modal, Progress, Typography } from 'antd';
import React, { useRef, useState } from 'react';
import PageWrapper from '../layout/PageWrapper';
import ProTable from '@ant-design/pro-table';
import { DownOutlined, ExclamationCircleOutlined, EditOutlined, DeleteOutlined } from '@ant-design/icons';
import HTTP from '../../../common/helpers/HTTP';
import Routes from '../../../common/helpers/Routes';
import Utils from '../../../common/helpers/Utils';
import Skill from './Skill';

const { confirm } = Modal;

const Skills = () => {
    const [loading, setLoading] = useState(false);
    const actionRef = useRef();
    const [modalVisible, setModalVisible] = useState(false);
    const [itemToEdit, setItemToEdit] = useState(null);

    const columns = [
        {
            title: 'Name',
            dataIndex: 'name',
            search: true,
            sorter: true,
            width: 170,
            ellipsis:true
        },
        {
            title: 'Proficiency',
            dataIndex: 'proficiency',
            align: 'center',
            sorter: true,
            search: true,
            width: 170,
            render: (text, row) => [
                <Progress
                    key={'progress'}
                    strokeColor={{
                        from: '#108ee9',
                        to: '#87d068',
                    }}
                    percent={row.proficiency}
                    status="active"
                />,
            ],
        },
        {
            title: 'Option',
            valueType: 'option',
            align: 'center',
            width: 170,
            fixed: 'right',
            render: (text, row) => [
                <Dropdown key="0" overlay={menu(row)} trigger={['click']}>
                    <a className="ant-dropdown-link" onClick={e => e.preventDefault()}>
                        Option <DownOutlined />
                    </a>
                </Dropdown>,
            ],
        }
    ];

    const showConfirm = (rows) => {
        let ids = [];
        rows.forEach(row => {
            ids.push(row.id);
        });
        confirm({
            confirmLoading: loading,
            title: `Do you want to delete ${ids.length == 1 ? 'this' : 'these'} ${ids.length == 1 ? 'item' : 'items'}?`,
            icon: <ExclamationCircleOutlined />,
            mask: true,
            onOk() {
                setLoading(true);
                HTTP.delete(Routes.api.admin.skills, {
                    params: {
                        ids: ids
                    }
                })
                .then(response => {
                    Utils.handleSuccessResponse(response, () => {
                        Utils.showTinyNotification(response.data.message, 'success');
                        actionRef.current?.reloadAndRest();
                    });
                })
                .catch((error) => {
                    Utils.handleException(error);
                }).finally(() => {
                    setLoading(false);
                });
            },
        });
    }

    const menu = (row) => (
        <Menu>
            <Menu.Item 
                key="0" 
                onClick={() => {
                    setItemToEdit(row);
                    setModalVisible(true);
                }}
                icon={<EditOutlined />}
            >
                Edit
            </Menu.Item>
            <Menu.Item 
                key="1"
                onClick={() => showConfirm([row])}
                icon={<DeleteOutlined />}
            >
                Delete
            </Menu.Item>
        </Menu>
    );

    return (
        <React.Fragment>
            <PageWrapper>
                <PageHeader
                    style={{padding: 0}}
                    title="Skills"
                    subTitle={
                        <Typography.Text
                            style={{ width: '100%', color: 'grey' }}
                            ellipsis={{ tooltip: 'Your professional skills' }}
                        >
                            Your professional skills
                        </Typography.Text>
                    }
                    extra={[
                        <Button key="add" type="primary" onClick={() => setModalVisible(true)}>
                            Add New
                        </Button>,
                    ]}
                >
                    <ProTable
                        columns={columns}
                        cardBordered={true}
                        showSorterTooltip={false}
                        scroll={{x: true}}
                        tableLayout={'fixed'}
                        pagination={{
                            showQuickJumper: true,
                            pageSize: 10
                        }}
                        rowSelection={{
                            // onChange: (_, selectedRows) => setSelectedRows(selectedRows),
                        }}
                        tableAlertRender={({ selectedRowKeys, onCleanSelected }) => (
                            <Space>
                                <span>
                                    Selected {selectedRowKeys.length} items
                                    <a
                                        style={{
                                            marginLeft: 8,
                                        }}
                                        onClick={onCleanSelected}
                                    >
                                        <strong>Cancel Selection</strong>
                                    </a>
                                </span>
                            </Space>
                        )}
                        tableAlertOptionRender={({ selectedRows }) => (
                            <Space>
                                <Button type="primary" onClick={() => showConfirm(selectedRows)}>Batch Deletion</Button>
                            </Space>
                        )}
                        actionRef={actionRef}
                        request={async (params, sorter) => {
                            return HTTP.get(Routes.api.admin.skills+'?page='+params.current, {
                                params: {
                                    params,
                                    sorter,
                                    columns
                                }
                            }).then(response => {
                                return Utils.handleSuccessResponse(response, () => {
                                    return response.data.payload
                                })
                            })
                            .catch(error => {
                                Utils.handleException(error);
                            })
                        }}
                        dateFormatter="string"
                        search={false}
                        rowKey="id"
                        options={{
                            search: true,
                        }}
                    />
                </PageHeader>
            </PageWrapper>
            {
                modalVisible && (
                    <Skill
                        title={itemToEdit ? 'Edit Skill' : 'Add Skill'}
                        itemToEdit={itemToEdit}
                        visible={modalVisible}
                        handleCancel={
                            () => {
                                setItemToEdit(null);
                                setModalVisible(false);
                            }
                        }
                        submitCallback={
                            () => {
                                setItemToEdit(null);
                                actionRef.current?.reloadAndRest();
                                setModalVisible(false);
                            }
                        }
                    />
                )
            }
        </React.Fragment>
    )
}

export default Skills;