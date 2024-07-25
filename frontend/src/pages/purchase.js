import {Global} from "../App";
import {useEffect, useState} from "react";
import axios from "axios";
import {Button, Descriptions, Form, Input, List, Radio, Select} from "antd";
import {CheckCircleOutlined, ExclamationCircleOutlined} from "@ant-design/icons";

export default function Purchase() {
    const [purchase, setPurchase] = useState([])
    const fetchPurchase = async (cond = {}) => {
        const { data } = await axios.post(Global.api + "/purchase-list", {
            filter: cond.filter === undefined ? "" : cond.filter,
            order: cond.order === undefined ? "" : cond.order
        })
        const d = data
        const purchase = []
        for (let val in d) {
            purchase.push(d[val])
        }
        setPurchase(purchase)
    }

    useEffect(() => {
        fetchPurchase()
    }, [])

    let filter = (values) => {
        let cond = {filter: {}, order: "new"}
        if (values.by !== "") {
            cond.filter.date = values.by
        }
        if (values.product !== "") {
            cond.filter.product_id = values.product
        }

        cond.order = values.order
        cond.filter.currency = values.currency

        fetchPurchase(cond)
    }

    return (
        <div>
            <div className="bg-white p-2 rounded-2xl ">
                <Form onFinish={filter} layout="inline">
                    <Form.Item name="by">
                        <Select
                            defaultValue=""
                            options={[
                                {
                                    value: "",
                                    label: "By date"
                                },
                                {
                                    value: "day",
                                    label: "Day",
                                },
                                {
                                    value: "week",
                                    label: "Week"
                                }
                        ]}>
                        </Select>
                    </Form.Item>
                    <Form.Item name="product">
                        <Input placeholder="Product"></Input>
                    </Form.Item>

                    <Form.Item name="currency">
                        <Radio.Group defaultValue="false">
                            <Radio.Button value="false">All</Radio.Button>
                            <Radio.Button value="true">Currency Only</Radio.Button>
                        </Radio.Group>
                    </Form.Item>

                    <Form.Item name="order">
                        <Select
                            defaultValue="new"
                            options={[
                            {
                                value: "new",
                                label: "New",
                            },
                            {
                                value: "old",
                                label: "Old",
                            }
                        ]}>
                        </Select>
                    </Form.Item>

                    <Form.Item>
                        <Button type="primary" htmlType="submit">
                            Submit
                        </Button>
                    </Form.Item>
                </Form>
                <List className="px-1"
                      itemLayout="horizontal"
                      dataSource={purchase}
                      renderItem={(item, index) => (
                          <List.Item>
                              <Descriptions title="Purchase">
                                  <Descriptions.Item label="Name">{item.name}</Descriptions.Item>
                                  <Descriptions.Item label="Telephone">{item.telephone}</Descriptions.Item>
                                  <Descriptions.Item label="Payment">
                                      {item.payment === 1 ? <CheckCircleOutlined /> : <ExclamationCircleOutlined /> }
                                  </Descriptions.Item>
                                  <Descriptions.Item label="Product">{item.product_id !== null ? item.product_id.name : "deleted"}</Descriptions.Item>
                                  <Descriptions.Item label="Date">{item.created_at}</Descriptions.Item>
                              </Descriptions>
                          </List.Item>
                      )}
                >

                </List>
            </div>
        </div>
    )
}