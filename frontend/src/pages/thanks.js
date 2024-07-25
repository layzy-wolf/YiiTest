import {Typography} from "antd";

export default function Thanks() {
    return (
        <div className="flex flex-col justify-center items-center">
            <Typography.Title level={1}>
                Thanks!
            </Typography.Title>
            <Typography.Text>
                Thanks for purchase
            </Typography.Text>
        </div>
    )
}