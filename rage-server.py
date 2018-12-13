#!/usr/bin/env python

# WebSockets server to play the game Rage

import asyncio
import json
import logging
import websockets
import random

serverHost = '192.168.0.3'
port = 16427

logging.basicConfig()
logging.getLogger().setLevel(logging.INFO)

USERS = set()
STATE = {
    'game_id': 0,
    'players_allowed': 0
}


##### SERVER EVENTS #####

# These 3 events are for the initial synchronization connection
def event_no_game_started():
    return json.dumps({'type': 'no_game_started'})
def event_game_full():
    return json.dumps({'type': 'game_full'})
def event_game_started():
    return json.dumps({'type': 'game_started', **STATE})

# This event is for syncing the number of online users after connect/disconnect
def users_event():
    return json.dumps({'type': 'users', 'count': len(USERS)})


##### NOTIFIERS #####

# General notify function for all events
async def notify_event(event):
    await asyncio.wait([user.send(event) for user in USERS])

# General notify function for all server events
async def notify_server_event(event_type, event_data = -1):
    if USERS:
        event = json.dumps({'type': event_type, 'data': event_data})
        await notify_event(event)

# Notifies other users of new connections
async def notify_users():
    if USERS:       # asyncio.wait doesn't accept an empty list
        await notify_server_event('users', len(USERS))


##### FUNCTIONS #####

async def register(websocket):
    USERS.add(websocket)
    await notify_users()

async def unregister(websocket):
    USERS.remove(websocket)
    await notify_users()

def update_state(eventJson):
    for key in eventJson:
        if key == 'type':
            continue
        STATE[key] = eventJson[key]
        logging.info(key + ' = ' + str(STATE[key]))

async def serverFunction(websocket, path):
    # register(websocket) sends user_event() to websocket
    await register(websocket)
    try:
        # check if a game is running or full when a user accesses the page
        if STATE['game_id'] == 0:
            await websocket.send(event_no_game_started())
        else:
            if len(USERS) > STATE['players_allowed']:
                await websocket.send(event_game_full())
            else:
                await websocket.send(event_game_started())

        # relay the command recieved from the Web Interface
        # update the STATE object if the command is update_state
        async for event in websocket:
            eventJson = json.loads(event)
            if eventJson['type'] == 'update_state':
                update_state(eventJson)
            await notify_event(event)

    finally:
        await unregister(websocket)


##### RUN SERVER #####

asyncio.get_event_loop().run_until_complete(websockets.serve(serverFunction, serverHost, port))
asyncio.get_event_loop().run_forever()