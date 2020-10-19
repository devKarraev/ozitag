import React, {useEffect, useState} from "react"

export const SingleView = (props) => {

    const [postData, setPostData] = useState(null)

    useEffect(() => {
        const postId = props.match.params.flatId
        async function fetchPostData() {
            const response = await fetch(`/api/posts/${postId}`)
            const data = await response.json()
            setPostData(data)
        }
        fetchPostData()
    }, [])

    const textAreatHandler = e => {
        setPostData({...postData, [postData.main_content]: e.target.value})
    }

    return (
        <div>
            {postData != null &&
                <textarea
                    onChange={e => textAreatHandler(e)}
                    value={postData.main_content.replace(/(<([^>]+)>)/gi, "")}
                />
            }

        </div>
    )
}
