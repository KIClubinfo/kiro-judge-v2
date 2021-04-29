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
            <div class="row2">
                <div class="cell">{this.props.name}</div>
                <div class="cell">{this.props.public_score}</div>
                <div class="cell">{this.props.classement}</div>
                <div class="cell">{this.props.hub}</div>
                <div class="cell">{this.props.type_equipe}</div>
            </div>
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
            <div class="container">
                <div class="wrap-table100" style="margin-top: 5vh;">
                    <div class="table">
                        <div class="row2 header">
                            <div class="cell">Nom d'équipe</div>
                            <div class="cell">Classement</div>
                            <div class="cell">Score</div>
                            <div class="cell">Hub</div>
                            <div class="cell">Type</div>
                        </div>
                            {
                                this.state.scores.map((element, index) => {
                                    return <Score name={element.nom} public_score={element.public_score} classement={element.classement} hub={element.hub} type_equipe={element.type_equipe} key={element.id} />
                                })
                            }
                    </div>
                </div>
            </div>
        );
    }
}

const element = <ScoreBoard />;
ReactDOM.render(element, document.getElementById('leaderboard'));