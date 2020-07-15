var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

// Fichier de config ? Modification à la main ? Variable d'environnement ?
// Tout cela ne me plait guère...
var WEBSOCKET_URL = "ws://cxhome.org:8125";

var Score = function (_React$Component) {
    _inherits(Score, _React$Component);

    function Score() {
        _classCallCheck(this, Score);

        return _possibleConstructorReturn(this, (Score.__proto__ || Object.getPrototypeOf(Score)).apply(this, arguments));
    }

    _createClass(Score, [{
        key: "render",
        value: function render() {
            return React.createElement(
                "tr",
                null,
                React.createElement(
                    "td",
                    { id: "team_name" },
                    this.props.name
                ),
                React.createElement(
                    "td",
                    { id: "team_score" },
                    this.props.score
                ),
                React.createElement(
                    "td",
                    { id: "team_classement" },
                    this.props.classement
                )
            );
        }
    }]);

    return Score;
}(React.Component);

var ScoreBoard = function (_React$Component2) {
    _inherits(ScoreBoard, _React$Component2);

    function ScoreBoard(props) {
        _classCallCheck(this, ScoreBoard);

        var _this2 = _possibleConstructorReturn(this, (ScoreBoard.__proto__ || Object.getPrototypeOf(ScoreBoard)).call(this, props));

        _this2.state = {
            scores: []
        };
        return _this2;
    }

    _createClass(ScoreBoard, [{
        key: "componentDidMount",
        value: function componentDidMount() {
            var _this3 = this;

            // Create WebSocket connection.
            this.socket = new WebSocket(WEBSOCKET_URL);

            // Connection opened
            this.socket.addEventListener('open', function (event) {
                console.log('Websocket connection is on!');
            });

            // Listen for messages
            this.socket.addEventListener('message', function (event) {
                console.log(event);
                _this3.setState({
                    scores: JSON.parse(event.data)
                });
            });
        }
    }, {
        key: "componentWillUnmount",
        value: function componentWillUnmount() {
            this.socket.close();
        }
    }, {
        key: "render",
        value: function render() {
            return React.createElement(
                "table",
                null,
                React.createElement(
                    "tbody",
                    null,
                    this.state.scores.map(function (element, index) {
                        return React.createElement(Score, { name: element.nom, score: element.score, classement: element.classement, key: element.id });
                    })
                )
            );
        }
    }]);

    return ScoreBoard;
}(React.Component);

var element = React.createElement(ScoreBoard, null);
ReactDOM.render(element, document.getElementById('leaderboard'));