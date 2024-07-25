import {Link, Outlet, Route, Routes} from "react-router-dom";
import Main from "./pages/main";
import Buy from "./pages/buy";
import Thanks from "./pages/thanks";
import Purchase from "./pages/purchase";
import Layout, {Content, Footer, Header} from "antd/lib/layout/layout";
import {Flex, Menu} from "antd";
import {AccountBookOutlined, CheckOutlined} from "@ant-design/icons";

export default function App() {
    return (
        <div>
            <Routes>
                <Route path="/" element={<Lay/>}>
                    <Route index element={<Main/>}></Route>
                    <Route path="/buy/:id/:currency_id" element={<Buy/>}></Route>
                    <Route path="/thanks" element={<Thanks/>}></Route>
                    <Route path="/purchase" element={<Purchase/>}></Route>
                </Route>
            </Routes>
        </div>
    );
}

function Lay() {
    return (
        <>
            <Layout className="min-h-screen flex justify-space">
                <Header className="flex content-center gap-3">
                    <h1 className="min-w-24 text-white font-sans text-5xl">
                        Logo
                    </h1>
                    <Menu theme="dark" selectedKeys={[0]} mode="horizontal" items={[
                        {
                            label: (
                                <Link to="/">
                                    Products
                                </Link>
                            ),
                            key: "/",
                            icon: <AccountBookOutlined/>,
                        },
                        {
                            label: (
                                <Link to="/purchase">
                                    Purchase
                                </Link>
                            ),
                            key: "/purchase",
                            icon: <CheckOutlined/>
                        }
                    ]}>
                    </Menu>
                </Header>
                <Layout>
                    <Content className="min-h-full m-5 min-h-fit rounded">
                        <Outlet/>
                    </Content>
                </Layout>
                <Footer>
                    simple footer ©{new Date().getFullYear()}
                </Footer>
            </Layout>
        </>
    )
}

export const Global = {
    api: "http://localhost:80/api"
}