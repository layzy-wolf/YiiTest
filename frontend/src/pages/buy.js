import {redirect, redirectDocument, useHref, useNavigate, useParams} from "react-router-dom";
import {Button, Form, Input, Radio} from "antd";
import axios from "axios";
import {Global} from "../App";

export default function Buy() {
    let data = useParams()
    let nav = useNavigate()

    let finish = (values) => {
        let id

        if (values.payment === "rub") {
            id = data.id
        } else {
            id = data.currency_id
        }

        axios.post(Global.api + "/purchase", {
            name: values.name,
            telephone: values.telephone,
            product_id: id
        }).then(res => {
            if (res.status === 200) {
                console.log(res.status)
                nav("/thanks")
            }
        })
    }

    return (
        <div className="flex justify-center">
            <Form
                onFinish={finish}
                autoComplete="off"
                style={{
                    minWidth: 300,
                    maxWidth: 600,
                }}
            >
                <Form.Item name="payment"
                    rules={[{
                        required: true,
                    }]}
                >
                    <Radio.Group>
                        <Radio.Button value="rub">Rubles</Radio.Button>
                        <Radio.Button value="usd">Dollar</Radio.Button>
                    </Radio.Group>
                </Form.Item>

                <Form.Item
                    label="Name"
                    name="name"
                    rules={[
                        {
                            required: true,
                            message: "Please input your name"
                        }
                    ]}
                >
                    <Input placeholder="Please input your name" />
                </Form.Item>

                <Form.Item
                    className="py-2"
                    label="Telephone"
                    name="telephone"
                    rules={[
                        {
                            required: true,
                            message: "Please input your Telephone",
                        }
                    ]}
                >
                    <Input addonBefore="+7" placeholder="Please input your Telephone" />
                </Form.Item>

                <Form.Item
                    wrapperCol={{
                        offset: 8,
                        span: 16,
                    }}
                >
                    <Button type="primary" htmlType="submit">
                        Submit
                    </Button>
                </Form.Item>
            </Form>
        </div>
    )
}