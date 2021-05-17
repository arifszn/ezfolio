import { Button, Menu, PageHeader, Space, Dropdown, Modal, Switch, Typography } from 'antd';
import React, { useRef, useState } from 'react';
import moment from 'moment';
import PageWrapper from '../layout/PageWrapper';
import ProTable from '@ant-design/pro-table';
import { 
    DownOutlined, 
    ExclamationCircleOutlined, 
    EyeOutlined, 
    DeleteOutlined, 
    SendOutlined,
    CheckOutlined,
    CloseOutlined
} from '@ant-design/icons';
import HTTP from '../../../common/helpers/HTTP';
import Routes from '../../../common/helpers/Routes';
import Utils from '../../../common/helpers/Utils';
import Message from './Message';

const { confirm } = Modal;

const Messages = () => {
    const [loading, setLoading] = useState(false);
    const actionRef = useRef();
    const [modalVisible, setModalVisible] = useState(false);
    const [itemToShow, setItemToShow] = useState(null);

    const repliedOnChangeHandler = (checked, item) => {
        HTTP.put(Routes.api.admin.messages+'/'+item.id, {
            id: item.id,
            name: item.name,
            email: item.email,
            subject: item.subject,
            body: item.body,
            replied: checked,
        })
        .then(response => {
            Utils.handleSuccessResponse(response, () => {
                Utils.showTinyNotification(response.data.message, 'success');
                actionRef.current?.reloadAndRest();
            });
        })
        .catch((error) => {
            Utils.handleException(error);
        });
    }

    const columns = [
        {
            title: 'Sender Name',
            dataIndex: 'name',
            search: true,
            sorter: true,
            width: 170,
            ellipsis:true
        },
        {
            title: 'Sender Email',
            dataIndex: 'email',
            sorter: true,
            search: true,
            width: 170,
            ellipsis:true
        },
        {
            title: 'Subject',
            dataIndex: 'subject',
            sorter: true,
            search: true,
            width: 170,
            ellipsis:true
        },
        {
            title: 'Body',
            dataIndex: 'body',
            sorter: true,
            search: true,
            ellipsis:true,
            hideInTable: true
        },
        {
            title: 'Received',
            dataIndex: 'created_at',
            sorter: true,
            search: false,
            ellipsis:true,
            width: 130,
            render: (text, row) => (
                moment(row.created_at).fromNow()
            )
        },
        {
            title: 'Replied',
            dataIndex: 'replied',
            valueType: 'replied',
            align: 'center',
            width: 130,
            sorter: true,
            tip: 'Reply back status. Change this after replying manually.',
            // eslint-disable-next-line react/display-name
            render: (text, row) => (
                <Switch
                    onChange={(checked) => repliedOnChangeHandler(checked, row)}
                    defaultChecked={parseInt(row.replied)}
                    checkedChildren={<CheckOutlined />}
                    unCheckedChildren={<CloseOutlined />}
                />
            )
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
                HTTP.delete(Routes.api.admin.messages, {
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
                    window.open(`mailto:${row.email}`, '_blank')
                }}
                icon={<SendOutlined />}
            >
                Reply
            </Menu.Item>
            <Menu.Item 
                key="1" 
                onClick={() => {
                    setItemToShow(row);
                    setModalVisible(true);
                }}
                icon={<EyeOutlined/>}
            >
                View
            </Menu.Item>
            <Menu.Item 
                key="2"
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
                    title="Messages"
                    subTitle={
                        <Typography.Text
                            style={{ width: '100%', color: 'grey' }}
                            ellipsis={{ tooltip: 'Messages from portfolio contact section' }}
                        >
                            Messages from portfolio contact section
                        </Typography.Text>
                    }
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
                        expandable={{
                            // eslint-disable-next-line react/display-name
                            expandedRowRender: record => <p style={{ margin: '0 17px' }}>Body: {record.body}</p>,
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
                            return HTTP.get(Routes.api.admin.messages+'?page='+params.current, {
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
                    <Message
                        title={'Message Details'}
                        data={itemToShow}
                        visible={modalVisible}
                        handleCancel={
                            () => {
                                setItemToShow(null);
                                setModalVisible(false);
                            }
                        }
                        submitCallback={
                            () => {
                                setItemToShow(null);
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

export default Messages;