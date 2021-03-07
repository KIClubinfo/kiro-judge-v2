import React from "react";

// TODO : ajouter le css
// TODO : ajouter le hub
// TODO : ajouter si la derniere instance etait valide

// Fichier de config ? Modification à la main ? Variable d'environnement ?
// Tout cela ne me plait guère...
const WEBSOCKET_URL = "ws://cxhome.org:8125"

class Score extends React.Component {
    render() {
        return (
            <tr>
                <td id="team_name">{this.props.name}</td>
                <td id="team_score">{this.props.score}</td>
                <td id="team_classement">{this.props.classement}</td>
            </tr>
        );
    }
}


export class ScoreBoard extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            scores: []
        }
    }

    componentDidMount() {
        // Create WebSocket connection.
        this.socket = new WebSocket(WEBSOCKET_URL);

        // Connection opened
        this.socket.addEventListener('open', function (event) {
            console.log('Websocket connection is on!');
        });

        // Listen for messages
        this.socket.addEventListener('message', (event) => {
            console.log(event);
            this.setState({
                scores: JSON.parse(event.data)
            })
        });
    }

    componentWillUnmount() {
        this.socket.close();
    }


    render() {
        return (
            <table>
                <tbody>
                    {
                        this.state.scores.map((element, index) => {
                            return <Score name={element.nom} score={element.score} classement={element.classement} key={element.id} />
                        })
                    }

                </tbody>
            </table>
        );
    }
}