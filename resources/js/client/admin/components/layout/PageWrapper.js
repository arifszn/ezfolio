import { Card } from "antd"

const PageWrapper = ({children, className, loading = false}) => {
    return (
        <Card bordered={false} bodyStyle={{padding: 0}} hoverable className={`${className} z-shadow`} loading={loading}>
            {children}
        </Card>
    )
}

export default PageWrapper;