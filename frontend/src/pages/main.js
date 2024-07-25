import {Global} from "../App";
import axios from "axios";
import {List, Typography} from "antd";
import {Link} from "react-router-dom";
import {useEffect, useState} from "react";

export default function Main() {
    const [products, setProducts] = useState([])
    const fetchProducts = async () => {
        const { data } = await axios.get(Global.api + "/products")
        const d = data
        const products = []
        for (let val in d) {
            products.push(d[val])
        }
        setProducts(products)
    }

    useEffect(() => {
        fetchProducts()
    }, [])

    return (
        <div className="bg-white p-2 rounded-2xl ">
            <List className="px-1"
                itemLayout="horizontal"
                dataSource={products}
                renderItem={(item, index) => (
                    <List.Item>
                        <List.Item.Meta
                            title={<Link to={"/buy/"+(index+1)+"/"+item.currency_id}>{item.name}</Link>}
                            description={item.description}
                        />
                        <div>
                            {item.price} <Typography.Text>₽</Typography.Text> / {item.currency} <Typography.Text>$</Typography.Text>
                        </div>
                    </List.Item>
                )}
            >

            </List>
        </div>
    )
}